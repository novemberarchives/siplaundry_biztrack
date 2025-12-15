<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $transaction->TransactionID }} | Sip Laundry</title>
    <style>
        /* Thermal Printer Optimization */
        @page {
            margin: 0;
            size: 58mm auto; /* Target 58mm width, auto height */
        }
        body {
            font-family: 'Courier New', Courier, monospace; /* Monospace for alignment */
            font-size: 12px;
            margin: 0;
            padding: 5px;
            color: #000;
            background: #fff;
            width: 58mm; /* Approximate width for screen viewing */
            line-height: 1.2;
        }
        
        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        /* Dashed Dividers common in receipts */
        .border-b { border-bottom: 1px dashed #000; }
        
        .py-1 { padding-top: 4px; padding-bottom: 4px; }
        .my-2 { margin-top: 8px; margin-bottom: 8px; }
        
        /* Flexbox for "Label ......... Value" layout */
        .flex { display: flex; justify-content: space-between; }
        
        /* Hide scrollbars and ensure fit on print */
        @media print {
            body { width: auto; margin: 0; padding: 0; }
            html, body { height: auto; overflow: hidden; }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Header -->
    <div class="text-center">
        <h1 class="font-bold text-lg uppercase my-2">Sip Laundry</h1>
        <p>123 Laundry Street</p>
        <p>Pasay City, Metro Manila</p>
        <p>0912-345-6789</p>
    </div>

    <div class="border-b my-2"></div>

    <!-- Meta Data -->
    <div>
        <div class="flex">
            <span>Order #:</span>
            <span class="font-bold">{{ $transaction->TransactionID }}</span>
        </div>
        <div class="flex">
            <span>Date:</span>
            <span>{{ \Carbon\Carbon::parse($transaction->DateCreated)->format('m/d/y h:i A') }}</span>
        </div>
        <div class="flex">
            <span>Cust:</span>
            <span>{{ substr($transaction->customer->Name, 0, 18) }}</span>
        </div>
    </div>

    <div class="border-b my-2"></div>

    <!-- Items -->
    <div>
        @foreach($transaction->transactionDetails as $detail)
            <div class="py-1">
                <div class="font-bold">{{ $detail->service->Name }}</div>
                <div class="flex">
                    <span>
                        {{-- Logic to show Weight vs Qty based on unit --}}
                        {{ $detail->Weight ? $detail->Weight . 'kg' : $detail->Quantity . 'pc' }} 
                        x {{ number_format($detail->PricePerUnit, 2) }}
                    </span>
                    <span class="font-bold">{{ number_format($detail->Subtotal, 2) }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="border-b my-2"></div>

    <!-- Totals -->
    <div class="py-1">
        <div class="flex font-bold text-lg">
            <span>TOTAL:</span>
            <span>P{{ number_format($transaction->TotalAmount, 2) }}</span>
        </div>
        <div class="flex py-1">
            <span>Status:</span>
            <span class="uppercase font-bold">{{ $transaction->PaymentStatus }}</span>
        </div>
        @if($transaction->PaymentStatus == 'Paid')
            <div class="flex" style="font-size: 10px;">
                <span>Paid On:</span>
                <span>{{ \Carbon\Carbon::parse($transaction->DatePaid)->format('m/d/y') }}</span>
            </div>
        @endif
    </div>

    <div class="border-b my-2"></div>

    <!-- Footer -->
    <div class="text-center my-2">
        <p class="font-bold">CLAIM STUB / RECEIPT</p>
        <p style="font-size: 10px; margin-top: 5px; text-align: justify;">
            Please check your items before leaving. 
            We are not responsible for shrinkage, fading, or color bleeding.
            Unclaimed items after 30 days will be donated.
        </p>
        <p class="my-2 font-bold">*** Thank You! ***</p>
    </div>

</body>
</html>