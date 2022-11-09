<?php

if (! function_exists('StyleSpreadsheet'))
{
	function StyleSpreadsheet($stylename)
	{
        $styleArray = [];
		switch($stylename){
            case 'boxheader': 
                $styleArray = [
                                'font' => [
                                    'bold' => true,
                                ],
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                                'borders' => [
                                    'top' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                    'left' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                    'right' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                    'bottom' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                ],
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'color' => [
                                        'argb' => 'FFFFFF00',
                                    ]
                                ],
                            ];
            
            break;

            case 'boxBody': 
                $styleArray = [
                                'alignment' => [
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                                'borders' => [
                                    'top' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                    'left' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                    'right' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                    'bottom' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                ]
                            ];
            
            break;
            
        }

        return $styleArray;
	}
}

?>