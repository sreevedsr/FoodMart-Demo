<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Status Updated</title>
</head>
<body style="margin:0; padding:0; background-color:#fafafa; font-family: 'Open Sans', sans-serif; color:#222222;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fafafa; padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0px 5px 22px rgba(0,0,0,0.04);">
                    <!-- Header -->
                    <tr>
                        <td align="center" bgcolor="#FFC43F" style="padding:20px; color:#fff; font-family:'Nunito', sans-serif;">
                            <h1 style="margin:0; font-size:24px;">Order Status Updated</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px;">
                            <p style="margin:0 0 15px; font-size:16px;">Hello <strong>{{ $order->user->name }}</strong>,</p>
                            <p style="margin:0 0 20px; font-size:16px;">Your order <strong>#{{ $order->id }}</strong> status has been updated.</p>

                            <!-- Order Details Table -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:12px; border:1px solid #FBFBFB; background-color:#eef1f3;"><strong>Order Status</strong></td>
                                    <td style="padding:12px; border:1px solid #FBFBFB;">{{ ucfirst($order->order_status) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px; border:1px solid #FBFBFB; background-color:#eef1f3;"><strong>Payment Status</strong></td>
                                    <td style="padding:12px; border:1px solid #FBFBFB;">{{ ucfirst($order->payment_status) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px; border:1px solid #FBFBFB; background-color:#eef1f3;"><strong>Total Amount</strong></td>
                                    <td style="padding:12px; border:1px solid #FBFBFB;">₹{{ $order->total_amount }}</td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <p style="text-align:center; margin:30px 0;">
                                <a href="{{ route('orders.show', $order->id) }}" style="display:inline-block; background-color:#FFC43F; color:#fff; text-decoration:none; padding:12px 25px; border-radius:6px; font-weight:bold; font-size:16px;">View Order</a>
                            </p>

                            <p style="font-size:16px; margin:0;">Thank you for shopping with <strong>FoodMart</strong>! We hope to see you again soon.</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" bgcolor="#eef1f3" style="padding:15px; font-size:14px; color:#727272;">
                            &copy; {{ date('Y') }} FoodMart. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
