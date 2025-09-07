<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FlipPaymentModal extends Component
{
    public $totalAmount;
    public $transaction;
    public $isVisible;

    /**
     * Create a new component instance.
     */
    public function __construct($totalAmount = 0, $transaction = null, $isVisible = false)
    {
        $this->totalAmount = $totalAmount;
        $this->transaction = $transaction;
        $this->isVisible = $isVisible;
    }

    /**
     * Get the available payment methods from Flip
     */
    public function getPaymentMethods()
    {
        return [
            [
                'code' => 'bca_va',
                'name' => 'BCA Virtual Account',
                'type' => 'bank_transfer',
                'icon' => '/images/payments/bca.png',
                'fee' => 4000
            ],
            [
                'code' => 'bni_va',
                'name' => 'BNI Virtual Account', 
                'type' => 'bank_transfer',
                'icon' => '/images/payments/bni.png',
                'fee' => 4000
            ],
            [
                'code' => 'bri_va',
                'name' => 'BRI Virtual Account',
                'type' => 'bank_transfer', 
                'icon' => '/images/payments/bri.png',
                'fee' => 4000
            ],
            [
                'code' => 'mandiri_va',
                'name' => 'Mandiri Virtual Account',
                'type' => 'bank_transfer',
                'icon' => '/images/payments/mandiri.png', 
                'fee' => 4000
            ],
            [
                'code' => 'permata_va',
                'name' => 'Permata Virtual Account',
                'type' => 'bank_transfer',
                'icon' => '/images/payments/permata.png',
                'fee' => 4000
            ],
            [
                'code' => 'cimb_va',
                'name' => 'CIMB Niaga Virtual Account',
                'type' => 'bank_transfer',
                'icon' => '/images/payments/cimb.png',
                'fee' => 4000
            ],
            [
                'code' => 'bsi_va',
                'name' => 'BSI Virtual Account',
                'type' => 'bank_transfer',
                'icon' => '/images/payments/bsi.png',
                'fee' => 4000
            ],
            [
                'code' => 'qris',
                'name' => 'QRIS',
                'type' => 'e_wallet',
                'icon' => '/images/payments/qris.png',
                'fee' => 0
            ],
            [
                'code' => 'shopeepay',
                'name' => 'ShopeePay',
                'type' => 'e_wallet', 
                'icon' => '/images/payments/shopeepay.png',
                'fee' => 0
            ],
            [
                'code' => 'gopay',
                'name' => 'GoPay',
                'type' => 'e_wallet',
                'icon' => '/images/payments/gopay.png', 
                'fee' => 0
            ],
            [
                'code' => 'ovo',
                'name' => 'OVO',
                'type' => 'e_wallet',
                'icon' => '/images/payments/ovo.png',
                'fee' => 0
            ],
            [
                'code' => 'dana',
                'name' => 'DANA', 
                'type' => 'e_wallet',
                'icon' => '/images/payments/dana.png',
                'fee' => 0
            ],
            [
                'code' => 'linkaja',
                'name' => 'LinkAja',
                'type' => 'e_wallet',
                'icon' => '/images/payments/linkaja.png',
                'fee' => 0
            ],
            [
                'code' => 'indomaret',
                'name' => 'Indomaret',
                'type' => 'retail',
                'icon' => '/images/payments/indomaret.png',
                'fee' => 5000
            ],
            [
                'code' => 'alfamart', 
                'name' => 'Alfamart',
                'type' => 'retail',
                'icon' => '/images/payments/alfamart.png',
                'fee' => 5000
            ]
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.flip-payment-modal');
    }
}