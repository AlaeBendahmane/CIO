<?php
require 'conf.php';
require_once 'helpers.php';
session_start();
// Security checks
isAuthQuery();
isAdminQuery();
require_once '../vendor/autoload.php';

use setasign\Fpdi\Fpdi;
use Smalot\PdfParser\Parser;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['pdf_file'])) {
    $tmpFile = $_FILES['pdf_file']['tmp_name'];
    $customTitle = $_POST['title'] ?? 'Fiche';

    // Array to track unique users who should receive a notification
    $notificationQueue = [];

    try {
        $parser = new Parser();
        $pdf = $parser->parseFile($tmpFile);
        $pages = $pdf->getPages();

        $stats = [
            "total_pages" => count($pages),
            "success"     => 0,
            "errors"      => 0,
            "skipped"     => 0,
            "details"     => []
        ];

        foreach ($pages as $index => $page) {
            $pageNumber = $index + 1;
            $text = $page->getText();

            // 1. Extract Agent Code (ID Fiscal)
            $code = '';
            if (preg_match('/(\d{4})\s+([A-Z\s]{5,})\s+TELEOPERATEUR/', $text, $matches)) {
                $code = trim($matches[1]);
            }

            if (empty($code)) {
                $stats['skipped']++;
                $stats['details'][] = "Page $pageNumber: Aucun code agent détecté dans le texte.";
                continue;
            }

            // 2. Find the Agent in Database
            $userStmt = $pdo->prepare("SELECT id FROM agents WHERE idFiscal = ? LIMIT 1");
            $userStmt->execute([$code]);
            $user = $userStmt->fetch();

            if (!$user) {
                $stats['errors']++;
                $stats['details'][] = "Page $pageNumber: Code $code détecté mais absent de la base de données.";
                continue;
            }

            // 3. Extract and Process the PDF Page
            try {
                $newPdf = new Fpdi();
                $newPdf->setSourceFile($tmpFile);
                $templateId = $newPdf->importPage($pageNumber);
                $newPdf->addPage();
                $newPdf->useTemplate($templateId);

                // Output to String and Encode
                $pdfContent = $newPdf->Output('S');
                $base64 = base64_encode($pdfContent);

                // 4. Insert into 'documents' table
                $ins = $pdo->prepare("INSERT INTO documents 
                    (name, contentType, type_document, account_doc_indice, base64, creationDate, creationHeure) 
                    VALUES (?, 'application/pdf', 'PDF', ?, ?, CURDATE(), CURTIME())");

                $fileName = $customTitle . ".pdf";

                if ($ins->execute([$fileName, $user['id'], $base64])) {
                    $stats['success']++;

                    // Add User ID to notification queue if not already there
                    if (!in_array($user['id'], $notificationQueue)) {
                        $notificationQueue[] = $user['id'];
                    }
                } else {
                    throw new Exception("Erreur lors de l'insertion en base de données.");
                }
            } catch (Exception $e) {
                $stats['errors']++;
                $stats['details'][] = "Page $pageNumber (Code $code): " . $e->getMessage();
            }
        }

        /**
         * 5. SEND NOTIFICATIONS
         * We trigger notifications only once per user, after all pages are processed.
         */

        if (!empty($notificationQueue)) {
            sendBulkNotification(
                'Nouveau document',
                "Je vous ai envoyé le document: " . $fileName . ".\nPour le visualiser, accédez à l’onglet 'Mes documents'.",
                $notificationQueue,
                $_SESSION['id']
            );

            // foreach ($notificationQueue as $userId) {

            //     // Adjust parameters based on your sendBulkNotification signature
            //     sendBulkNotification(
            //         'Nouveau document',
            //         "Je vous ai envoyé le document: " . $fileName . ".\nPour le visualiser, accédez à l’onglet 'Mes documents'.",
            //         $userId,
            //         $_SESSION['id']
            //     );
            // }
        }

        // Final Response to Frontend
        echo json_encode([
            "status" => "success",
            "stats"  => [
                "total"   => $stats['total_pages'],
                "success" => $stats['success'],
                "failed"  => $stats['errors'],
                "skipped" => $stats['skipped']
            ],
            "log" => $stats['details']
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Erreur critique lors du traitement du PDF: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Aucun fichier PDF valide n'a été reçu."
    ]);
}
