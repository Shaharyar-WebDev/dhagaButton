<?php

namespace App\Filament\Support\Actions;

use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use App\Filament\Resources\Purchase\DeliveryOrders\DeliveryOrderResource;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;

class CustomAction
{
    public static function verifyStatus(): Action
    {
        return Action::make('verify')
            ->label('Verify')
            ->visible(fn($record) => $record->status === 'draft')
            ->requiresConfirmation()
            ->action(function ($record) {
                DB::transaction(function () use ($record) {
                    $record->status = 'verified';
                    $record->save();
                });

                Notification::make()
                    ->title('Verified')
                    ->success()
                    ->body('Status has been updated to verified.')
                    ->send();
            })
            ->color('success')
            ->icon('heroicon-o-check');
    }

    public static function createDeliveryOrder()
    {
        return Action::make('create_delivery_order')
            ->label('Create Delivery order')
            ->color('info')
            // ->visible(
            //     fn($record) =>
            //     $record->canCreateDo()
            //     &&
            //     $record->hasRemainingDoQty()
            // )
            ->icon(DeliveryOrderResource::getNavigationIcon())
            ->action(function ($record, $data, $livewire) {
                // Redirect to the Stock Transpufer Record resource creation page
                $livewire->redirect(
                    DeliveryOrderResource::getUrl('create', [
                        'purchase_order_id' => $record->id,
                    ]),
                    navigate: spa_mode()
                );
            });
    }

    public static function createGoodReceivedNote()
    {
        return Action::make('create_grn')
            ->label('Create Goods Received Note')
            ->color('success')
            // ->visible(
            //     fn($record) =>
            //     !$record->canCreateDo()
            //     &&
            //     $record->hasRemainingGrnQty()
            // )
            ->icon(GoodsReceivedNoteResource::getNavigationIcon())
            ->action(function ($record, $data, $livewire) {
                // Redirect to the Stock Transpufer Record resource creation page
                $livewire->redirect(
                    GoodsReceivedNoteResource::getUrl('create', [
                        'purchase_order_id' => $record->id,
                    ]),
                    navigate: spa_mode()
                );
            });
    }
}
