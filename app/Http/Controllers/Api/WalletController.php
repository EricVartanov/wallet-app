<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransactionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function deposit(DepositRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $user = User::find($data['user_id']);

            if (! $user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            $user->balance += $data['amount'];
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => TransactionTypeEnum::DEPOSIT,
                'amount' => $data['amount'],
                'comment' => $data['comment'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'user_id' => $user->id,
                'balance' => $user->balance,
                'message' => 'Deposit successful'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function withdraw(WithdrawRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $user = User::find($data['user_id']);

            if (! $user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            if ($user->balance < $data['amount']) {
                return response()->json([
                    'message' => 'Insufficient balance'
                ], 409);
            }

            $user->balance -= $data['amount'];
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'type' => TransactionTypeEnum::WITHDRAW,
                'amount' => $data['amount'],
                'comment' => $data['comment'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'user_id' => $user->id,
                'balance' => $user->balance,
                'message' => 'Withdraw successful'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function transfer(TransferRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $sender = User::find($data['from_user_id']);
            $recipient = User::find($data['to_user_id']);

            if (! $sender || ! $recipient) {
                return response()->json(['message' => 'User not found'], 404);
            }

            if ($sender->balance < $data['amount']) {
                return response()->json(['message' => 'Insufficient balance'], 409);
            }

            // Sender balance out
            $sender->balance -= $data['amount'];
            $sender->save();

            Transaction::create([
                'user_id' => $sender->id,
                'from_user_id' => $sender->id,
                'to_user_id' => $recipient->id,
                'type' => TransactionTypeEnum::TRANSFER_OUT,
                'amount' => $data['amount'],
                'comment' => $data['comment'] ?? null,
            ]);

            // Recipient balance in
            $recipient->balance += $data['amount'];
            $recipient->save();

            Transaction::create([
                'user_id' => $recipient->id,
                'from_user_id' => $sender->id,
                'to_user_id' => $recipient->id,
                'type' => TransactionTypeEnum::TRANSFER_IN,
                'amount' => $data['amount'],
                'comment' => $data['comment'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'from_user_id' => $sender->id,
                'to_user_id' => $recipient->id,
                'amount' => $data['amount'],
                'sender_balance' => $sender->balance,
                'recipient_balance' => $recipient->balance,
                'message' => 'Transfer successful'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function balance(User $user_id): JsonResponse
    {
        $user = User::findOrFail($user_id);

        return response()->json([
            'user_id' => $user->id,
            'balance' => $user->balance,
        ]);
    }
}
