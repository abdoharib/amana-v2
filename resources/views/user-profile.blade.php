<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap');
        
        body {
            font-family: 'Tajawal', sans-serif;
        }
        
        :root {
            --primary: #ffae00;
            --secondary: #fa496e;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-25 to-amber-50 min-h-screen py-8 px-4">
    <div class="max-w-4xl mx-auto">
        @if(isset($isTestMode) && $isTestMode)
            <!-- Test Mode Banner -->
            <div class="text-white rounded-lg p-4 mb-6 shadow-lg border-2" style="background: linear-gradient(to right, #ffae00, #f59e0b); border-color: #f59e0b;">
                <div class="flex items-center justify-center">
                    <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="font-bold text-lg">
                        🧪 وضع الاختبار (رقم المستخدم: 5)
                    </div>
                </div>
            </div>
        @endif

        @if(isset($error))
            <!-- Global Error Message -->
            <div class="bg-red-50 border-r-4 border-red-500 rounded-lg p-6 mb-6 shadow-md">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-red-800 font-semibold text-lg">خطأ</h3>
                        <p class="text-red-700 mt-1">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($user)
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 text-shadow-xl mb-2">الملف الشخصي</h1>
            </div>

            <!-- User Information Card -->
            <div class="border border-gray-200 bg-white rounded-2xl shadow-xl shadow-gray-75 p-8 mb-6 transform hover:scale-[1.01] transition-transform duration-300">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg" style="background: linear-gradient(to bottom right, #ffae00, #f59e0b);">
                        {{ substr($user->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="mr-4">
                        <h2 class="text-2xl font-bold text-gray-800">معلومات المستخدم</h2>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-1">الاسم</p>
                                <p class="text-gray-800 font-semibold text-lg">{{ $user->name ?? 'غير محدد' }}</p>
                            </div>
                            <svg class="w-8 h-8" style="color: #ffae00;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-1">البريد الإلكتروني</p>
                                <p class="text-gray-800 font-semibold text-lg break-all">{{ $user->email ?? 'غير محدد' }}</p>
                            </div>
                            <svg class="w-8 h-8" style="color: #ffae00;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-1">رقم الهاتف</p>
                                <p class="text-gray-800 font-semibold text-lg">{{ $user->phone_number ?? 'غير محدد' }}</p>
                            </div>
                            <svg class="w-8 h-8" style="color: #ffae00;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Account Status -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm mb-1">حالة الحساب</p>
                                <p class="text-gray-800 font-semibold text-lg">
                                    @if($user->is_activated)
                                        <span class="text-green-600">نشط</span>
                                    @else
                                        <span class="text-yellow-600">غير نشط</span>
                                    @endif
                                </p>
                            </div>
                            <svg class="w-8 h-8 {{ $user->is_activated ? 'text-green-500' : 'text-yellow-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription Information Card -->
            <div class="border border-gray-200 bg-white rounded-2xl shadow-xl p-8 transform hover:scale-[1.01] transition-transform duration-300">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-white shadow-lg" style="background: linear-gradient(to bottom right, #fa496e, #ec4899);">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="mr-4">
                        <h2 class="text-2xl font-bold text-gray-800">معلومات الاشتراك</h2>
                    </div>
                </div>

                @if($subscriptionError)
                    <!-- Subscription Error -->
                    <div class="bg-yellow-50 border-r-4 border-yellow-500 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-yellow-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="text-yellow-700">{{ $subscriptionError }}</p>
                        </div>
                    </div>
                @elseif($subscription)
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <!-- Subscription Status -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border-2" style="border-color: #ffae00;">
                            <p class="text-gray-600 text-sm mb-2">حالة الاشتراك</p>
                            <p class="text-2xl font-bold">
                                @if(strtolower($subscription['status']) === 'active')
                                    <span class="text-green-600">نشط</span>
                                @else
                                    <span class="text-red-600">{{ $subscription['status'] }}</span>
                                @endif
                            </p>
                        </div>

                        <!-- Expiration Date -->
                        <div class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg p-4 border-2" style="border-color: #fa496e;">
                            <p class="text-gray-600 text-sm mb-2">تاريخ الانتهاء</p>
                            <p class="text-2xl font-bold" style="color: #fa496e;">{{ $subscription['expiration_date'] }}</p>
                        </div>

                        <!-- Subscription Name -->
                        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-4 border-2" style="border-color: #f59e0b;">
                            <p class="text-gray-600 text-sm mb-2">نوع الاشتراك</p>
                            <p class="text-xl font-bold" style="color: #ffae00;">{{ $subscription['subscription_name'] }}</p>
                        </div>
                    </div>

                    @if(strtolower($subscription['status']) === 'active')
                        <!-- Cancel Subscription Button -->
                        <div class="mt-8 p-6 bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl border-2" style="border-color: #fa496e;">
                            <h3 class="text-lg font-bold text-gray-800 mb-3">إلغاء الاشتراك</h3>
                            <p class="text-gray-600 mb-4">إذا كنت ترغب في إلغاء اشتراكك، يمكنك القيام بذلك من خلال النقر على الزر أدناه.</p>
                            
                            <button 
                                onclick="handleCancelSubscription()"
                                class="w-full md:w-auto text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center mx-auto"
                                style="background: linear-gradient(to right, #fa496e, #dc2626);"
                                onmouseover="this.style.background='linear-gradient(to right, #dc2626, #b91c1c)'"
                                onmouseout="this.style.background='linear-gradient(to right, #fa496e, #dc2626)'"
                            >
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                إلغاء الاشتراك
                            </button>
                        </div>
                    @endif
                @else
                    <!-- No Subscription Data -->
                    <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-gray-600 text-lg">لا توجد معلومات اشتراك متاحة</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <script>
        function handleCancelSubscription() {
            if (confirm('هل أنت متأكد من رغبتك في إلغاء الاشتراك؟')) {
                // You can redirect to the unsubscribe endpoint
                window.location.href = '/api/client/unsubscribe?phone={{ $user->phone_number ?? '' }}';
                
                // Or you can show a message
                alert('سيتم توجيهك إلى صفحة إلغاء الاشتراك');
            }
        }
    </script>
</body>
</html>

