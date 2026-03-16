<?php
ignore_user_abort(true);
ini_set('max_execution_time', 0); //300
set_time_limit(0); //300
ini_set('memory_limit', '512M');
header('Access-Control-Expose-Headers: Content-Disposition');
require __DIR__ . '/../vendor/autoload.php';
require_once 'helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table_data'])) {
    try {
        if (ob_get_length()) ob_clean();

        // 1. Setup Data and Month Info
        $data = json_decode($_POST['table_data'], true);
        $month = (int)($_POST['month'] ?? date('n'));
        $year = (int)($_POST['year'] ?? date('Y'));
        $site = "CIO";

        $frenchMonths = [
            1 => "Janvier",
            2 => "Février",
            3 => "Mars",
            4 => "Avril",
            5 => "Mai",
            6 => "Juin",
            7 => "Juillet",
            8 => "Août",
            9 => "Septembre",
            10 => "Octobre",
            11 => "Novembre",
            12 => "Décembre"
        ];
        $monthName = $frenchMonths[$month] ?? "Export";

        // 2. Load the Template
        $templatePath = __DIR__ . '/../dist/assets/templates/template_pointage.xlsx';
        if (!file_exists($templatePath)) {
            throw new Exception("Template not found at: " . $templatePath);
        }
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        // 3. Update Dynamic Title (Cell F2)
        $sheet->setCellValue('F2', "Pointage " . $monthName . " " . $year);

        // 4. Generate Headers & Full Column Coloring
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $totalRows = count($data) + 6; // Rows start at 7, so data ends at count + 6
        $startCol = 6; // Starts at Column F
        $daysFr = ['Mon' => 'L', 'Tue' => 'M', 'Wed' => 'M', 'Thu' => 'J', 'Fri' => 'V', 'Sat' => 'S', 'Sun' => 'D'];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $colLetter = Coordinate::stringFromColumnIndex($startCol);
            $timestamp = mktime(0, 0, 0, $month, $day, $year);
            $dayInitial = $daysFr[date('D', $timestamp)];

            // Row 5: Day Number
            $sheet->setCellValue($colLetter . '5', $day);
            // Row 6: Day Initial
            $sheet->setCellValue($colLetter . '6', $dayInitial);

            // Apply Coloring to the ENTIRE Column (Rows 5 to TotalRows)
            if ($dayInitial === 'S' || $dayInitial === 'D') {
                $color = ($dayInitial === 'S') ? 'FF97CDFF' : 'FFF7AF7E'; // Blue for S, Orange for D
                $sheet->getStyle($colLetter . '5:' . $colLetter . $totalRows)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($color);
            }

            // Always apply borders to headers
            $sheet->getStyle($colLetter . '5:' . $colLetter . '6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);

            //
            $headerStyle = $sheet->getStyle($colLetter . '5:' . $colLetter . '6');
            $headerStyle->getFont()->setBold(true);
            $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $headerStyle->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            //

            $startCol++;
        }

        // Add "Totals" headers after the days
        $totals = ["Jours", "Assiduité", "Avance", "Prime", "CDP", "Remarque"];
        foreach ($totals as $totalLabel) {
            $colLetter = Coordinate::stringFromColumnIndex($startCol);
            $sheet->setCellValue($colLetter . '5', $totalLabel);
            $sheet->mergeCells($colLetter . '5:' . $colLetter . '6');
            //
            $totalStyle = $sheet->getStyle($colLetter . '5:' . $colLetter . '6');
            $totalStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $totalStyle->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $totalStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
            //
            $sheet->getStyle($colLetter . '5')->getFont()->setBold(true);
            $sheet->getStyle($colLetter . '6')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_MEDIUM);
            $startCol++;
        }

        // 5. Fill Data Rows (Starting Row 7)
        $rowNum = 7;
        foreach ($data as $rowData) {
            $colIndex = 1;
            foreach ($rowData as $cellValue) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex);

                $cleanValue = is_array($cellValue) ? ($cellValue['value'] ?? '') : (string)$cellValue;
                $commentText = is_array($cellValue) ? ($cellValue['comment'] ?? '') : '';

                $sheet->setCellValue($colLetter . $rowNum, $cleanValue);
                //

                $borders = $sheet->getStyle($colLetter . $rowNum)->getBorders();
                $borders->getLeft()->setBorderStyle(Border::BORDER_MEDIUM);
                $borders->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
                $borders->getTop()->setBorderStyle(Border::BORDER_THIN);
                $borders->getBottom()->setBorderStyle(Border::BORDER_THIN);

                //
                if (!empty($commentText)) {
                    $sheet->getComment($colLetter . $rowNum)->getText()->createTextRun($commentText);
                }

                // Apply Conditional Formatting for Status (Overrides weekend color if applicable)
                if (in_array($cleanValue, ['A', 'C', 'SB'])) {
                    $cellStyle = $sheet->getStyle($colLetter . $rowNum);
                    switch ($cleanValue) {
                        case 'A':
                            $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFE0001');
                            $cellStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
                            break;
                        case 'C':
                            $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF01');
                            $cellStyle->getFont()->setBold(true)->getColor()->setARGB('FF000000');
                            break;
                        case 'SB':
                            $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF71309F');
                            $cellStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
                            break;
                    }
                }
                $colIndex++;
            }
            $rowNum++;
        }

        // 6. Freeze Panes and Auto-size
        $sheet->freezePane('F7');
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 7. Export File
        $fileName = "Pointage_" . $site . "_" . $monthName . "_" . $year . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_end_clean();
        $writer->save('php://output');

        ///
        $host = 'localhost';
        $db   = 'pointagedb';
        $user = 'root';
        $pass = '';
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        logActivity($pdo, 'export_pointage', $idFiscal, ['comment' => ''], $month, $year, $idFiscal);


        exit;
    } catch (Exception $e) {
        if (ob_get_length()) ob_clean();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}
