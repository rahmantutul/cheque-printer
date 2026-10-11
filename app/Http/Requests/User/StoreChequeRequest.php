<?php
declare(strict_types=1);
namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;
class StoreChequeRequest extends FormRequest { public function authorize(): bool { return auth()->check(); } public function rules(): array { return ['bank_id'=>['required','exists:banks,id'],'template_id'=>['required','exists:cheque_templates,id'],'payee_name'=>['required','string','max:255'],'amount'=>['required','numeric','min:0.01','max:999999999.99'],'cheque_date'=>['required','date','before_or_equal:'.now()->addDays(90)->toDateString()],'memo'=>['nullable','string','max:255'],'status'=>['nullable','in:draft']]; } public function withValidator($validator): void { $validator->after(function($v): void { if($this->template_id && !\App\Models\ChequeTemplate::whereKey($this->template_id)->where('bank_id',$this->bank_id)->where('created_by',auth()->id())->exists()) $v->errors()->add('template_id','The template must belong to the selected bank and your account.'); }); } }
