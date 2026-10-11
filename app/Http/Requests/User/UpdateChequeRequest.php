<?php
declare(strict_types=1);
namespace App\Http\Requests\User;
use Illuminate\Foundation\Http\FormRequest;
class UpdateChequeRequest extends FormRequest { public function authorize(): bool { return auth()->check() && $this->route('cheque')->user_id===auth()->id() && $this->route('cheque')->status==='draft'; } public function rules(): array { return (new StoreChequeRequest)->rules(); } public function withValidator($validator): void { (new StoreChequeRequest)->withValidator($validator); } }
