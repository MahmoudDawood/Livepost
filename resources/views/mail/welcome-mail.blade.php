@component('mail::message')
# Welcome {{ $name }}

We are thrilled to welcome you to {{ config('app.name') }}! 🎉

Thank you for joining our community. We're excited to have you on board!

Feel free to explore all the features available on our platform.

Once again, welcome, and we hope you have a fantastic experience with us!

Best regards,<br>
{{ config('app.name') }}
@endcomponent