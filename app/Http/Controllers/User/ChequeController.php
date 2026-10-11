<?php
declare(strict_types=1);
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreChequeRequest;
use App\Http\Requests\User\UpdateChequeRequest;
use App\Models\Bank;
use App\Models\Cheque;
use App\Models\ChequeTemplate;
use App\Services\AmountInWordsService;
use App\Services\ChequeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
class ChequeController extends Controller {
 public function __construct(private readonly ChequeService $service, private readonly AmountInWordsService $words) {}
 public function index(Request $request): View { $cheques=Cheque::where('user_id',auth()->id())->with(['bank','template'])->when($request->bank_id,fn($q,$v)=>$q->where('bank_id',$v))->when($request->status,fn($q,$v)=>$q->where('status',$v))->when($request->from,fn($q,$v)=>$q->whereDate('cheque_date','>=',$v))->when($request->to,fn($q,$v)=>$q->whereDate('cheque_date','<=',$v))->when($request->q,fn($q,$v)=>$q->where('payee_name','like','%'.$v.'%'))->latest()->paginate(20)->withQueryString(); $banks=Bank::orderBy('name')->get(); return view('user.cheques.index',compact('cheques','banks')); }
 public function create(Request $request): View { $banks=Bank::orderBy('name')->get(); $templates=ChequeTemplate::forUser(auth()->id())->get(); return view('user.cheques.create',compact('banks','templates')); }
 public function store(StoreChequeRequest $request): RedirectResponse { $cheque=$this->service->create($request->validated(),auth()->id()); return redirect()->route('user.cheques.show',$cheque)->with('success','Cheque created successfully.'); }
 public function show(Cheque $cheque): View { Gate::authorize('view',$cheque); $cheque->load(['bank','template']); return view('user.cheques.show',compact('cheque')); }
 public function edit(Cheque $cheque): View { Gate::authorize('update',$cheque); $banks=Bank::orderBy('name')->get(); $templates=ChequeTemplate::forUser(auth()->id())->get(); return view('user.cheques.edit',compact('cheque','banks','templates')); }
 public function update(UpdateChequeRequest $request, Cheque $cheque): RedirectResponse { $this->service->update($cheque,$request->validated()); return redirect()->route('user.cheques.show',$cheque)->with('success','Cheque updated successfully.'); }
 public function destroy(Cheque $cheque): RedirectResponse { Gate::authorize('delete',$cheque); $this->service->delete($cheque); return redirect()->route('user.cheques.index')->with('success','Cheque deleted successfully.'); }
 public function amountInWords(Request $request): JsonResponse { $amount=(float)$request->query('amount',0); if($amount<=0)return response()->json(['full'=>'','line_1'=>null,'line_2'=>null]); $template=ChequeTemplate::find($request->query('template_id')); $two=$template && isset($template->field_positions['amount_words_1'],$template->field_positions['amount_words_2']); $full=$this->words->toEnglish($amount,(string)config('cheque.currency_word','Taka'),(string)config('cheque.minor_currency_word','Paisa')); $lines=$this->service->splitAmountInWords($full,$two); return response()->json(['full'=>$full,'line_1'=>$lines['line_1'],'line_2'=>$lines['line_2']]); }
 public function markPrinted(Cheque $cheque): RedirectResponse { Gate::authorize('update',$cheque); $this->service->markAsPrinted($cheque); return back()->with('success','Cheque marked as printed.'); }
 public function print(Cheque $cheque): View { Gate::authorize('view',$cheque); return view('user.cheques.print-placeholder',compact('cheque')); }
 public function pdf(Cheque $cheque): View { Gate::authorize('view',$cheque); return view('user.cheques.pdf-placeholder',compact('cheque')); }
}
