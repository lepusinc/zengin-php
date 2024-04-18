<?php
declare(strict_types=1);

namespace ZenginPhp;

/**
 * Class Convert
 * @package ZenginPhp
 */
class Convert
{
    const FROM_ENCODING = 'SJIS';
    const TO_ENCODING = 'UTF-8';

    /**
     * Convert CSV to array.
     *
     * @param string $csv
     * @return array
     */
    public static function toArray(string $csv): array
    {
        return self::convertCsvToArray($csv);
    }

    /**
     * Convert CSV to JSON.
     *
     * @param string $csv
     * @return string
     */
    public static function toJson(string $csv): string
    {
        return json_encode(self::convertCsvToArray($csv), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Convert CSV to array.
     *
     * @param string $csv
     * @return array
     */
    protected static function convertCsvToArray(string $csv): array
    {
        $records = explode("\n", $csv);
        $records = self::formatArray($records);

        $banks = [];
        foreach ($records as $record) {
            $branch = self::mapBankRecord($record);

            $bankCode = (string)$branch['bank_code'];
            if (!isset($banks[$bankCode])) {
                $banks[$bankCode] = [
                    'bank_code' => $bankCode,
                    'bank_name' => $branch['bank_name'],
                    'bank_name_kana' => $branch['bank_name_kana'],
                    'branches' => [],
                ];
            }

            $branchCode = (string)$branch['branch_code'];
            $banks[$bankCode]['branches'][] = [
                'branch_code' => $branchCode,
                'branch_name' => $branch['branch_name'],
                'branch_name_kana' => $branch['branch_name_kana'],
                'zip' => (string)$branch['zip'],
                'address' => $branch['address'],
                'tel' => (string)$branch['tel'],
                'kokanjo_number' => (string)$branch['kokanjo_number'],
                'order_code' => (string)$branch['order_code'],
            ];
        }

        return $banks;
    }

    /**
     * Format and encode the data inside array.
     *
     * @param array $records
     * @return array
     */
    protected static function formatArray(array $records): array
    {
        // Remove empty lines.
        $records = array_filter($records, function ($line) {
            return !empty($line);
        });

        // Explode by comma.
        $records = array_map(function ($line) {
            return explode(',', $line);
        }, $records);

        // Convert encoding.
        mb_convert_variables(
            self::TO_ENCODING,
            self::FROM_ENCODING,
            $records
        );

        // Remove return code and double quotes.
        $records = array_map(function ($lines) {
            return array_map(function ($line) {
                $line = rtrim($line, "\n\r");
                return trim($line, '"');
            }, $lines);
        }, $records);

        return $records;
    }

    /**
     * Map bank record as array.
     *
     * @param array $record
     * @return array
     */
    protected static function mapBankRecord(array $record): array
    {
        return [
            'bank_code' => $record[0],
            'branch_code' => $record[1],
            'bank_name' => $record[3],
            'bank_name_kana' => $record[2],
            'branch_name' => $record[5],
            'branch_name_kana' => $record[4],
            'zip' => $record[6],
            'address' => $record[7],
            'tel' => $record[8],
            'kokanjo_number' => $record[9],
            'order_code' => $record[10],
        ];
    }
}