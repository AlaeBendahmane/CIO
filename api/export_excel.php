<?php
ignore_user_abort(true);
ini_set('max_execution_time', 0); //300
set_time_limit(0); //300
ini_set('memory_limit', '512M');
header('Access-Control-Expose-Headers: Content-Disposition');
ob_start();
require __DIR__ . '/../vendor/autoload.php';
include './session_info.php';
include './helpers.php';
session_start();
isAuthQuery();
isAdminQuery();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table_data'])) {
    try {
        if (ob_get_length()) ob_clean();

        $data = json_decode($_POST['table_data'], true);
        $nestedHeaders = json_decode($_POST['nested_headers'], true);
        $placeholders = ["", "", "", "", "", ""];
        $row1 = array_merge(($nestedHeaders['row1'] ?? []), $placeholders,); //$nestedHeaders['row1'] ?? [];
        $row2 = $nestedHeaders['row2'] ?? [];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- 0. IMAGE IN ROWS 1-4 ---
        if (file_exists('../dist/assets/img/Proximus_logo.png')) {
            $drawing = new Drawing();
            $drawing->setPath('../dist/assets/img/Proximus_logo.png');
            $drawing->setCoordinates('A1');
            $drawing->setHeight(70); // Fits nicely within 4 rows
            $drawing->setWorksheet($sheet);
        }

        //--------------
        $month = $_POST['month'] ?? date('F'); // e.g., "Janvier"
        $year = $_POST['year'] ?? date('Y');   // e.g., "2026"
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
            12 => "Décembre",
            13 => "None"
        ];
        $monthName = $frenchMonths[(int)$month ?? 13] ?? "Export";


        // 1. Merge the cells for the Title
        // F2 to AK2 for the top part, F3 to AK3 for the bottom (or merge all into one big box)
        $sheet->mergeCells('F2:AK3');

        // 2. Set the Text (using your dynamic variables)
        $sheet->setCellValue('F2', "Pointage " . $monthName . " " . $year);

        // 3. Apply Styling
        $titleStyle = $sheet->getStyle('F2:AK3');

        // Text Alignment
        $titleStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $titleStyle->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Font Styling
        $titleStyle->getFont()->setBold(true)->setSize(26)->getColor()->setARGB('FF000000');

        // Background White
        $titleStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFFFF');

        // Thick Black Border Rectangle
        $titleStyle->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THICK)->getColor()->setARGB('FF000000');

        //--------
        // --- 1. DYNAMIC ROW 1 (Shifted to Row 5) ---
        $currentCol = 1;
        foreach ($row1 as $index => $item) {
            $label = is_array($item) ? ($item['label'] ?? '') : (string)$item;
            $colspan = is_array($item) ? ($item['colspan'] ?? 1) : 1;

            if ($label === "Agent") {
                $currentCol = 3;
            }
            if ($label === "1") {
                $currentCol = 6;
            }

            $colLetter = Coordinate::stringFromColumnIndex($currentCol);
            $sheet->setCellValue($colLetter . '5', $label); // Target Row 5

            if ($colspan > 1) {
                $endCol = Coordinate::stringFromColumnIndex($currentCol + $colspan - 1);
                $sheet->mergeCells($colLetter . "5:" . $endCol . "5");
            }

            $style1 = $sheet->getStyle($colLetter . '5');
            $style1->getFont()->setBold(true)->setSize(14);
            $style1->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $style1->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD9D9D9');

            $currentCol += $colspan;
        }

        // --- 2. RENDER ROW 2 (Shifted to Row 6) ---
        $colIndex = 1;
        foreach ($row2 as $item) {
            $colLetter = Coordinate::stringFromColumnIndex($colIndex);
            $label = is_array($item) ? ($item['label'] ?? '') : (string)$item;

            $sheet->setCellValue($colLetter . '6', $label); // Target Row 6

            $style2 = $sheet->getStyle($colLetter . '6');
            $style2->getFont()->setBold(true)->setSize(12);
            $style2->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $style2->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // if (preg_match('/(S|D)/i', $label)) {
            if (strcasecmp($label, 'S') === 0 || strcasecmp($label, 'D') === 0) {
                // $style2->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFCCCC');

                switch (trim((string)$label)) {
                    case 'S':
                        $style2->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF97CDFF');
                        $style2->getFont()->setBold(true)->getColor()->setARGB('FF000000');
                        break;
                    case 'D':
                        $style2->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF7AF7E');
                        $style2->getFont()->setBold(true)->getColor()->setARGB('FF000000');
                        break;
                }



                ///
            } else {
                $style2->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE9ECEF');
            }
            $colIndex++;
        }

        // --- 3. DATA ROWS (Starting Row 7) ---
        $rowNum = 7; // Target Row 7
        foreach ($data as $rowData) {
            $colIndex = 1;
            foreach ($rowData as $cellValue) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex);
                ///
                // Default values
                $cleanValue = '';
                $commentText = '';
                // Check if $cellValue is the object we sent from JS
                if (is_array($cellValue) && isset($cellValue['comment'])) {
                    $cleanValue = (string)$cellValue['value'];
                    $commentText = (string)$cellValue['comment'];
                } else {
                    ///
                    $cleanValue = (is_array($cellValue) || is_object($cellValue)) ? '' : (string)$cellValue;
                }
                $sheet->setCellValue($colLetter . $rowNum, $cleanValue);
                ///
                if (!empty($commentText)) {
                    $sheet->getComment($colLetter . $rowNum)
                        ->getText()->createTextRun($commentText);

                    // Optional: set a fixed size for the comment box
                    $sheet->getComment($colLetter . $rowNum)->setWidth('200pt')->setHeight('100pt');
                }

                ///
                $cellStyle = $sheet->getStyle($colLetter . $rowNum);
                $cellStyle->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $cellStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                // 
                if (isset($row2[$colIndex - 1])) {

                    $dayLabel = is_array($row2[$colIndex - 1]) ? ($row2[$colIndex - 1]['label'] ?? '') : $row2[$colIndex - 1];
                    // if (preg_match('/(S|D)/i', (string)$dayLabel)) {
                    //     $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF97CDFF');
                    // }

                    switch (trim((string)$dayLabel)) {
                        case 'S':
                            $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF97CDFF');
                            $cellStyle->getFont()->setBold(true)->getColor()->setARGB('FF000000');
                            break;
                        case 'D':
                            $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF7AF7E');
                            $cellStyle->getFont()->setBold(true)->getColor()->setARGB('FF000000');
                            break;
                    }
                }
                switch (trim($cleanValue)) {
                    case 'A':
                        // Red background, White bold text
                        $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFE0001');
                        $cellStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
                        break;

                    case 'C':
                        // Yellow background, Black bold text
                        $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF01');
                        $cellStyle->getFont()->setBold(true)->getColor()->setARGB('FF000000');
                        break;

                    case 'SB':
                        // Purple background, White bold text
                        $cellStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF71309F');
                        $cellStyle->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
                        break;
                }
                $colIndex++;
            }
            $rowNum++;
        }


        //////
        $sheet->getStyle('1:6')->getFont()->setBold(true);
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:E' . $lastRow)->getFont()->setBold(true);
        $sheet->freezePane('F7');

        // --- 4. AUTO-SIZE & OUTPUT ---
        $highestCol = $sheet->getHighestColumn();
        $highestColNum = Coordinate::columnIndexFromString($highestCol);

        for ($i = 1; $i <= $highestColNum; $i++) {
            $colLetter = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        $thickBorderStyle = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        // 1. Thick border around the Header area (A5 to LastCol 6)
        // $sheet->getStyle('A5:' . $highestCol . '6')->applyFromArray($thickBorderStyle);

        // 2. Thick border around the Agent Info (A5 to E[LastRow])
        // $sheet->getStyle('A5:E' . $lastRow)->applyFromArray($thickBorderStyle);

        // 3. Thick border around the entire Table Data
        // $sheet->getStyle('A5:' . $highestCol . $lastRow)->applyFromArray($thickBorderStyle);

        // 4. Specific Thick vertical line between Agent info and Days
        // $sheet->getStyle('E5:E' . $lastRow)->getBorders()->getRight()->setBorderStyle(Border::BORDER_THICK);

        // 



        // 2. Construct the filename
        $fileName = "Pointage_" . $site . "_" . $monthName . "_" . $year . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        $writer = new Xlsx($spreadsheet);
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




        //////
        exit;
    } catch (Exception $e) {
        // If something crashes, send 500 status and the error message
        ob_end_clean();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}
