<?php
declare(strict_types=1);

namespace Tests\Unit\Api;

use PHPUnit\Framework\TestCase;
use ZenginPhp\Convert;

class ConvertTest extends TestCase
{
    /**
     * @covers \ZenginPhp\Parse::toArray
     */
    public function test_toArray(): void
    {
        $banks = Convert::toArray($this->getCsv());

        $this->assertCount(2, $banks);

        $this->assertArrayHasKey('0288', $banks);
        $this->assertArrayHasKey('0289', $banks);

        $this->assertSame('三菱ＵＦＪ信託銀行', $banks['0288']['bank_name']);
        $this->assertSame('みずほ信託銀行', $banks['0289']['bank_name']);

        $this->assertCount(3, $banks['0288']['branches']);
        $this->assertCount(3, $banks['0289']['branches']);

        $this->assertSame('本店営業部', $banks['0288']['branches'][0]['branch_name']);
        $this->assertSame('丸の内出張所', $banks['0288']['branches'][1]['branch_name']);
        $this->assertSame('上野支店', $banks['0288']['branches'][2]['branch_name']);
        $this->assertSame('本店営業部', $banks['0289']['branches'][0]['branch_name']);
        $this->assertSame('大森支店', $banks['0289']['branches'][1]['branch_name']);
        $this->assertSame('渋谷支店', $banks['0289']['branches'][2]['branch_name']);
    }

    /**
     * @covers \ZenginPhp\Parse::toJson
     */
    public function test_toJson(): void
    {
        $banksJson = Convert::toJson($this->getCsv());

        $banks = json_decode($banksJson, true);

        $this->assertCount(2, $banks);

        $this->assertArrayHasKey('0288', $banks);
        $this->assertArrayHasKey('0289', $banks);

        $this->assertSame('三菱ＵＦＪ信託銀行', $banks['0288']['bank_name']);
        $this->assertSame('みずほ信託銀行', $banks['0289']['bank_name']);

        $this->assertCount(3, $banks['0288']['branches']);
        $this->assertCount(3, $banks['0289']['branches']);

        $this->assertSame('本店営業部', $banks['0288']['branches'][0]['branch_name']);
        $this->assertSame('丸の内出張所', $banks['0288']['branches'][1]['branch_name']);
        $this->assertSame('上野支店', $banks['0288']['branches'][2]['branch_name']);
        $this->assertSame('本店営業部', $banks['0289']['branches'][0]['branch_name']);
        $this->assertSame('大森支店', $banks['0289']['branches'][1]['branch_name']);
        $this->assertSame('渋谷支店', $banks['0289']['branches'][2]['branch_name']);
    }

    protected function getCsv(): string
    {
        $csv = <<<CSV
"0288","110","ﾐﾂﾋﾞｼUFJｼﾝﾀｸ","三菱ＵＦＪ信託銀行","ﾎﾝﾃﾝ","本店営業部","100-8212","東京都千代田区丸の内１－４－５","03-3212-1211","5001","1","1"
"0288","110","ﾐﾂﾋﾞｼUFJｼﾝﾀｸ","三菱ＵＦＪ信託銀行","ﾏﾙﾉｳﾁｼｭｯﾁｮｳｼｮ","丸の内出張所","100-8212","東京都千代田区丸の内１－４－５","03-3212-1211","5001","1","2"
"0288","140","ﾐﾂﾋﾞｼUFJｼﾝﾀｸ","三菱ＵＦＪ信託銀行","ｳｴﾉ","上野支店","110-0005","東京都台東区上野３－２３－６","03-3831-0116","5001","1","1"
"0289","010","ﾐｽﾞﾎｼﾝﾀｸ","みずほ信託銀行","ﾎﾝﾃﾝ","本店営業部","100-8241","東京都千代田区丸の内１－３－３","03-4335-0801","5001","1","1"
"0289","021","ﾐｽﾞﾎｼﾝﾀｸ","みずほ信託銀行","ｵｵﾓﾘ","大森支店","143-0023","東京都大田区山王２－５－１３","03-3773-0331","5001","1","1"
"0289","022","ﾐｽﾞﾎｼﾝﾀｸ","みずほ信託銀行","ｼﾌﾞﾔ","渋谷支店","150-0002","東京都渋谷区渋谷１－２４－１６","03-3409-6421","5001","1","1"
CSV;
        // Return SJIS encoded CSV.
        return mb_convert_encoding($csv, 'SJIS', 'UTF-8');
    }
}