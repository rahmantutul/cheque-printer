<?php
declare(strict_types=1);
namespace App\Services;
use InvalidArgumentException;
class AmountInWordsService
{
    private const ONES = ['Zero','One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen','Seventeen','Eighteen','Nineteen'];
    private const TENS = [2=>'Twenty',3=>'Thirty',4=>'Forty',5=>'Fifty',6=>'Sixty',7=>'Seventy',8=>'Eighty',9=>'Ninety'];
    public function __construct(private readonly ?CurrencyService $currencies = null) {}
    public function toEnglish(float $amount, ?string $currencyCode = null, ?string $legacyMinor = null): string
    {
        if ($legacyMinor === null && $currencyCode === '') throw new InvalidArgumentException('Currency words cannot be empty.');
        $service = $this->currencies ?? new CurrencyService();
        try { $legacyConfigured = config('cheque.currency_word'); } catch (\Throwable) { $legacyConfigured = null; }
        $currency = $legacyMinor !== null ? ['currency_word'=>$currencyCode ?? 'Taka','minor_currency_word'=>$legacyMinor,'numbering_system'=>'indian'] : ($legacyConfigured !== null ? ['currency_word'=>(string) $legacyConfigured,'minor_currency_word'=>(string) config('cheque.minor_currency_word','Paisa'),'numbering_system'=>'indian'] : ($service->find($currencyCode ?: 'BDT') ?? $service->default()));
        if (trim((string)($currency['currency_word'] ?? '')) === '' || trim((string)($currency['minor_currency_word'] ?? '')) === '') throw new InvalidArgumentException('Currency words cannot be empty.');
        [$major,$minor] = $this->splitAmount($amount); $parts=[];
        if ($major > 0 || $minor === 0) $parts[] = $this->englishInteger($major,(string)($currency['numbering_system'] ?? 'indian')).' '.$currency['currency_word'];
        if ($minor > 0) $parts[] = $this->englishInteger($minor,'western').' '.$currency['minor_currency_word'];
        return implode(' and ',$parts).' Only';
    }
    private function splitAmount(float $amount): array { if (!is_finite($amount)||$amount<0||$amount>9999999999999.99) throw new InvalidArgumentException('Amount must be finite and between 0 and 9999999999999.99.'); $minor=(int)round($amount*100,0,PHP_ROUND_HALF_UP); return [intdiv($minor,100),$minor%100]; }
    private function englishInteger(int $number,string $system): string { $scales=$system==='western'?[1000000000=>'Billion',1000000=>'Million',1000=>'Thousand',100=>'Hundred']:[10000000=>'Crore',100000=>'Lakh',1000=>'Thousand',100=>'Hundred']; foreach($scales as $scale=>$label) if($number >= $scale) return $this->englishInteger(intdiv($number,$scale),$system).' '.$label.(($number%$scale)>0?' '.$this->englishInteger($number%$scale,$system):''); if($number<20)return self::ONES[$number]; return self::TENS[intdiv($number,10)].(($number%10)>0?' '.self::ONES[$number%10]:''); }
}
