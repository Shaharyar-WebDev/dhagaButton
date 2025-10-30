<?php

namespace App\Filament\Support\Actions;

use Filament\Actions\Action;
use App\Models\Master\RawMaterial;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\Purchase\DeliveryOrders\DeliveryOrderResource;
use App\Filament\Resources\Purchase\GoodsReceivedNotes\GoodsReceivedNoteResource;
use App\Filament\Resources\Purchase\StockTransferRecords\StockTransferRecordResource;

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
            ->visible(
                fn($record) =>
                $record->canCreateDo()
                &&
                $record->hasRemainingDoQty()
            )
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

    public static function receiveYarn()
    {
        return Action::make('receive_yarn')
            // ->label('')
            ->color('success')
            ->icon(GoodsReceivedNoteResource::getNavigationIcon())
            ->action(function ($data, $livewire) {
                // Redirect to the Stock Transpufer Record resource creation page
                $livewire->redirect(
                    GoodsReceivedNoteResource::getUrl('create', [
                        'raw_material_id' => RawMaterial::whereHas('type', function ($query) {
                        $query->where('name', 'like', 'twisted_yarn');
                    })->first()?->id,
                    ]),
                    navigate: spa_mode()
                );
            });
    }

    public static function sendToDyer()
    {
        return Action::make('send_to_dyer')
            // ->label('')
            ->color('info')
            ->icon(StockTransferRecordResource::getNavigationIcon())
            ->action(function ($data, $livewire) {
                // Redirect to the Stock Transpufer Record resource creation page
                $livewire->redirect(
                    StockTransferRecordResource::getUrl('create', [
                        'raw_material_id' => RawMaterial::whereHas('type', function ($query) {
                        $query->where('name', 'like', 'twisted_yarn');
                    })->first()?->id,
                    ]),
                    navigate: spa_mode()
                );
            });
    }

    public static function unlock()
    {
        return Action::make('unlock')
            ->label('Unlock Record')
            ->visible(fn($record) => $record->locked)
            ->requiresConfirmation()
            ->action(function ($record) {
                DB::transaction(function () use ($record) {
                    $record->locked = false;
                    $record->saveQuietly();
                });

                Notification::make()
                    ->title('Verified')
                    ->success()
                    ->body('Record has been unlocked.')
                    ->send();
            })
            ->color('info')
            ->icon('heroicon-o-lock-open');
    }

    public static function viewAttachments($imagePath)
    {
        return Action::make('view_attachments')
            ->icon('heroicon-o-photo')
            ->color('info')
            ->schema([
                FileUpload::make('attachments')
                    ->label('Attachments')
                    ->directory($imagePath)
                    ->disk('public')
                    ->visibility('public')
                    // ->multiple()
                    ->openable()
                    ->downloadable()
                    // ->placeholder(null)
                    ->previewable()
                    ->disabled()
                    ->deletable(false)
                    ->dehydrated(false),
            ])
            ->slideOver()
            ->mountUsing(function ($form, $record) {
                $attachments = $record->attachments ?? [];

                $form->fill(['attachments' => $attachments]);
            })
            ->modalSubmitAction(false)
            ->modalWidth('3xl');
    }
}
