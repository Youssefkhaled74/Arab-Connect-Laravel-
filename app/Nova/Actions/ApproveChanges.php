<?php

namespace App\Nova\Actions;

use App\Models\Day;
use App\Models\Branch;
use App\Models\DayChange;
use App\Models\BranchPayment;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use App\Models\BranchPaymentChange;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\File;

class ApproveChanges extends Action
{
    use InteractsWithQueue, Queueable;

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $branch =  Branch::find($model->branch_id);
            // if ($branch->imgs) {
            //     $oldImages = explode(',', $model->old_imgs);
            //     $images = explode(',', $branch->imgs);
            //     foreach ($images as $image) {
            //         if(!in_array($image, $oldImages)){
            //             $imagePath = public_path($image);
            //             if (File::exists($imagePath)) {
            //                 File::delete($imagePath);
            //             }
            //         }
            //     }
            // }

            // if($model->tax_card){
            //     $taxCardPath = public_path($branch->tax_card);
            //     if (File::exists($taxCardPath)) {
            //         File::delete($taxCardPath);
            //     }
            // }
            // if($model->commercial_register){
            //     $commercialRegisterPath = public_path($branch->commercial_register);
            //     if (File::exists($commercialRegisterPath)) {
            //         File::delete($commercialRegisterPath);
            //     }
            // }
            $branch->update([
                'name' => $model->name ?? $branch->name,
                'email' => $model->email ?? $branch->email,
                'mobile' => $model->mobile ?? $branch->mobile,
                'location' => $model->location ?? $branch->location,
                'map_location' => $model->map_location ?? $branch->map_location,
                'lat' => $model->lat ?? $branch->lat,
                'lon' => $model->lon ?? $branch->lon,
                'face' => $model->face ?? $branch->face,
                'insta' => $model->insta ?? $branch->insta,
                'tiktok' => $model->tiktok ?? $branch->tiktok,
                'website' => $model->website ?? $branch->website,
                'tax_card' => $model->tax_card ?? $branch->tax_card,
                'commercial_register' => $model->commercial_register ?? $branch->commercial_register,
                'imgs' => $model->imgs ?? $branch->imgs,
                'all_days' => $model->all_days ?? $branch->all_days,
            ]);
            $dayChanges = DayChange::where('branch_id', $model->id)->exists();
            if ($dayChanges) {
                $day = Day::where('branch_id', $model->branch_id)->delete();
                foreach ($model->dayChanges as $day) {
                    Day::create([
                        'branch_id' => $branch->id,
                        'day' => $day->day,
                        'from' => $day->from,
                        'to' => $day->to,
                    ]);
                }
            }
            $paymentChanges = BranchPaymentChange::where('branch_id', $model->id)->exists();
            if ($paymentChanges) {
                $payment = BranchPayment::where('branch_id', $model->branch_id)->delete();
                foreach ($model->payment_methods as $payment) {
                    BranchPayment::create([
                        'branch_id' => $branch->id,
                        'payment_method_id' => $payment->payment_method_id,
                    ]);
                }
            }
            $model->is_activate = 1;
            $model->save();
        }

        return Action::message('Changes approved successfully.');
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
    public function authorizedToRun(Request $request, $model)
    {
        return true; // or your custom logic
    }
}
