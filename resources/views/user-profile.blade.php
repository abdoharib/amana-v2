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
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg" style="background: linear-gradient(to bottom right, #ffae00, #f59e0b);">
                            {{ substr($user->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="mr-4">
                            <h2 class="text-2xl font-bold text-gray-800">معلومات المستخدم</h2>
                        </div>
                    </div>
                    <button 
                        onclick="openEditProfileModal()"
                        class="cursor-pointer text-white font-bold py-2 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center"
                        style="background: linear-gradient(to right, #ffae00, #f59e0b);"
                        onmouseover="this.style.background='linear-gradient(to right, #f59e0b, #d97706)'"
                        onmouseout="this.style.background='linear-gradient(to right, #ffae00, #f59e0b)'"
                    >
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        تعديل
                    </button>
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
                                @elseif(strtolower($subscription['status']) === 'cancel')
                                    <span class="text-red-600">تم إلغاء الأشتراك</span>
                                @else
                                    <span class="text-amber-700">{{ $subscription['status'] }}</span>
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
                            <p class="text-xl font-bold" style="color: #c58a0a;">{{ $subscription['subscription_name'] }}</p>
                        </div>
                    </div>

                    @if(strtolower($subscription['status']) === 'active')
                        <!-- Cancel Subscription Button -->
                        <div class="mt-8 p-6 bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl border-2" style="border-color: #fa496e;">
                            <h3 class="text-lg font-bold text-gray-800 mb-3">إلغاء الاشتراك</h3>
                            <p class="text-gray-600 mb-4">إذا كنت ترغب في إلغاء اشتراكك، يمكنك القيام بذلك من خلال النقر على الزر أدناه.</p>

                            <button 
                                onclick="handleCancelSubscription()"
                                class="cursor-pointer w-full md:w-auto text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center mx-auto"
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
                    @else
                        <!-- Subscribe Button (when not active) -->
                        <div class="mt-8 p-6 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl border-2" style="border-color: #ffae00;">
                            <h3 class="text-lg font-bold text-gray-800 mb-3">تفعيل الاشتراك</h3>
                            <p class="text-gray-600 mb-4">للاستمتاع بجميع مزايا التطبيق، يمكنك تفعيل اشتراكك من خلال النقر على الزر أدناه.</p>

                            <button 
                                onclick="handleActivateSubscription()"
                                class="cursor-pointer w-full md:w-auto text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center mx-auto"
                                style="background: linear-gradient(to right, #ffae00, #f59e0b);"
                                onmouseover="this.style.background='linear-gradient(to right, #f59e0b, #d97706)'"
                                onmouseout="this.style.background='linear-gradient(to right, #ffae00, #f59e0b)'"
                            >
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                تفعيل الاشتراك
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

    <!-- Confirmation Modal (Unsubscribe) -->
    <div id="confirmModal" class="hidden fixed inset-0 bg-[rgba(0,0,0,0.2)] z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background: linear-gradient(to bottom right, #fa496e, #ec4899);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">تأكيد الإلغاء</h3>
                <p class="text-gray-600">هل أنت متأكد من رغبتك في إلغاء الاشتراك؟ سيتم إرسال رمز التحقق إلى هاتفك.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeConfirmModal()" class="cursor-pointer flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-lg transition-colors">
                    إلغاء
                </button>
                <button onclick="requestUnsubscribeOtp()" class="cursor-pointer flex-1 px-6 py-3 text-white font-bold rounded-lg transition-colors" style="background: linear-gradient(to right, #fa496e, #dc2626);">
                    متابعة
                </button>
            </div>
        </div>
    </div>

    <!-- Activation Confirmation Modal -->
    <div id="activateConfirmModal" class="hidden fixed inset-0 bg-[rgba(0,0,0,0.2)] z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background: linear-gradient(to bottom right, #ffae00, #f59e0b);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">تأكيد التفعيل</h3>
                <p class="text-gray-600">هل أنت متأكد من رغبتك في تفعيل الاشتراك؟ سيتم إرسال رمز التحقق إلى هاتفك.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeActivateConfirmModal()" class="cursor-pointer flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-lg transition-colors">
                    إلغاء
                </button>
                <button onclick="requestActivateOtp()" class="cursor-pointer flex-1 px-6 py-3 text-white font-bold rounded-lg transition-colors" style="background: linear-gradient(to right, #ffae00, #f59e0b);">
                    متابعة
                </button>
            </div>
        </div>
    </div>

    <!-- OTP Modal -->
    <div id="otpModal" class="hidden fixed inset-0 bg-[rgba(0,0,0,0.2)] z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background: linear-gradient(to bottom right, #ffae00, #f59e0b);">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">أدخل رمز التحقق</h3>
                <p class="text-gray-600 mb-4">تم إرسال رمز مكون من 4 أرقام إلى هاتفك</p>
                
                <!-- OTP Input -->
                <div class="flex justify-center gap-2 mb-4" dir="ltr">
                    <input type="text" maxlength="1" id="otp1" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none" oninput="handleOtpInput(this, 'otp2')" onkeydown="handleOtpBackspace(event, this, null)">
                    <input type="text" maxlength="1" id="otp2" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none" oninput="handleOtpInput(this, 'otp3')" onkeydown="handleOtpBackspace(event, this, 'otp1')">
                    <input type="text" maxlength="1" id="otp3" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none" oninput="handleOtpInput(this, 'otp4')" onkeydown="handleOtpBackspace(event, this, 'otp2')">
                    <input type="text" maxlength="1" id="otp4" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none" oninput="handleOtpInput(this, null)" onkeydown="handleOtpBackspace(event, this, 'otp3')">
                </div>
                
                <p id="otpError" class="text-red-600 text-sm mb-4 min-h-[1.25rem]"></p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeOtpModal()" class="cursor-pointer flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-lg transition-colors">
                    إلغاء
                </button>
                <button id="otpConfirmBtn" onclick="confirmAction()" class="cursor-pointer flex-1 px-6 py-3 text-white font-bold rounded-lg transition-colors" style="background: linear-gradient(to right, #ffae00, #f59e0b);">
                    تأكيد
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="hidden fixed inset-0 bg-[rgba(0,0,0,0.2)] z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 transform transition-all">
            <div class="text-center">
                <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background: linear-gradient(to bottom right, #ffae00, #f59e0b);">
                    <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800" id="loadingText">جاري المعالجة...</h3>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="hidden fixed inset-0 bg-[rgba(0,0,0,0.2)] z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 id="successTitle" class="text-2xl font-bold text-gray-800 mb-2">تمت العملية بنجاح</h3>
                <p id="successMessage" class="text-gray-600">تمت العملية بنجاح</p>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="hidden fixed inset-0 bg-[rgba(0,0,0,0.2)] z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">حدث خطأ</h3>
                <p id="errorMessage" class="text-gray-600">فشل إلغاء الاشتراك. يرجى المحاولة مرة أخرى.</p>
            </div>
            <button onclick="closeErrorModal()" class="cursor-pointer w-full px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-lg transition-colors">
                إغلاق
            </button>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="hidden fixed inset-0 bg-[rgba(0,0,0,0.2)] z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 transform transition-all">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white shadow-lg" style="background: linear-gradient(to bottom right, #ffae00, #f59e0b);">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mr-4">تعديل الملف الشخصي</h3>
                </div>
                <button onclick="closeEditProfileModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Error messages container -->
            <div id="profileEditErrors" class="hidden bg-red-50 border-r-4 border-red-500 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 ml-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div id="profileEditErrorsList" class="text-red-700 text-sm"></div>
                </div>
            </div>

            <form id="editProfileForm" onsubmit="handleProfileUpdate(event)" autocomplete="off">
                <!-- Name Field -->
                <div class="mb-4">
                    <label for="editName" class="block text-gray-700 font-semibold mb-2">الاسم</label>
                    <input 
                        type="text" 
                        id="editName" 
                        name="name"
                        value="{{ $user->name ?? '' }}"
                        required
                        maxlength="255"
                        autocomplete="off"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none transition-colors"
                        placeholder="أدخل الاسم"
                    >
                </div>

                <!-- Email Field -->
                <div class="mb-6">
                    <label for="editEmail" class="block text-gray-700 font-semibold mb-2">البريد الإلكتروني</label>
                    <input 
                        type="email" 
                        id="editEmail" 
                        name="email"
                        value="{{ $user->email ?? '' }}"
                        required
                        maxlength="255"
                        autocomplete="off"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none transition-colors"
                        placeholder="أدخل البريد الإلكتروني"
                    >
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button 
                        type="button"
                        onclick="closeEditProfileModal()" 
                        class="cursor-pointer flex-1 px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-lg transition-colors"
                    >
                        إلغاء
                    </button>
                    <button 
                        type="submit"
                        class="cursor-pointer flex-1 px-6 py-3 text-white font-bold rounded-lg transition-colors" 
                        style="background: linear-gradient(to right, #ffae00, #f59e0b);"
                    >
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const phoneNumber = '{{ $user->phone_number ?? '' }}';
        let currentAction = 'unsubscribe'; // 'subscribe' or 'unsubscribe'

        // ==================== Modal Functions ====================
        
        function handleCancelSubscription() {
            currentAction = 'unsubscribe';
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function handleActivateSubscription() {
            currentAction = 'subscribe';
            document.getElementById('activateConfirmModal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        function closeActivateConfirmModal() {
            document.getElementById('activateConfirmModal').classList.add('hidden');
        }

        function closeOtpModal() {
            document.getElementById('otpModal').classList.add('hidden');
            clearOtpInputs();
        }

        function closeErrorModal() {
            document.getElementById('errorModal').classList.add('hidden');
        }

        function openEditProfileModal() {
            document.getElementById('editProfileModal').classList.remove('hidden');
            // Clear any previous errors
            document.getElementById('profileEditErrors').classList.add('hidden');
            document.getElementById('profileEditErrorsList').innerHTML = '';
        }

        function closeEditProfileModal() {
            document.getElementById('editProfileModal').classList.add('hidden');
            // Reset form to original values
            document.getElementById('editProfileForm').reset();
            // Clear any error messages
            document.getElementById('profileEditErrors').classList.add('hidden');
            document.getElementById('profileEditErrorsList').innerHTML = '';
        }

        function showLoading(text = 'جاري المعالجة...') {
            document.getElementById('loadingText').textContent = text;
            document.getElementById('confirmModal').classList.add('hidden');
            document.getElementById('activateConfirmModal').classList.add('hidden');
            // Don't hide otpModal - loading modal will overlay on top
            document.getElementById('loadingModal').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingModal').classList.add('hidden');
        }

        function showSuccess(title, message) {
            document.getElementById('successTitle').textContent = title || 'تمت العملية بنجاح';
            document.getElementById('successMessage').textContent = message || 'تمت العملية بنجاح';
            document.getElementById('successModal').classList.remove('hidden');
        }

        function showError(message) {
            document.getElementById('errorMessage').textContent = message || 'حدث خطأ. يرجى المحاولة مرة أخرى.';
            document.getElementById('errorModal').classList.remove('hidden');
        }

        // ==================== OTP Input Handling ====================
        
        function clearOtpInputs() {
            document.getElementById('otp1').value = '';
            document.getElementById('otp2').value = '';
            document.getElementById('otp3').value = '';
            document.getElementById('otp4').value = '';
            document.getElementById('otpError').textContent = '';
        }

        function handleOtpInput(input, nextFieldId) {
            // Only allow numbers
            input.value = input.value.replace(/[^0-9]/g, '');
            
            if (input.value && nextFieldId) {
                document.getElementById(nextFieldId).focus();
            }
        }

        function handleOtpBackspace(event, input, prevFieldId) {
            if (event.key === 'Backspace' && !input.value && prevFieldId) {
                document.getElementById(prevFieldId).focus();
            }
        }

        function getOtpValue() {
            const otp1 = document.getElementById('otp1').value;
            const otp2 = document.getElementById('otp2').value;
            const otp3 = document.getElementById('otp3').value;
            const otp4 = document.getElementById('otp4').value;
            return otp1 + otp2 + otp3 + otp4;
        }

        // ==================== UNSUBSCRIBE Flow ====================
        
        // Step 1: Request OTP for Unsubscribe
        async function requestUnsubscribeOtp() {
            if (!phoneNumber) {
                showError('رقم الهاتف غير متوفر');
                return;
            }

            showLoading('جاري إرسال رمز التحقق...');

            try {
                const response = await fetch('/api/client/unsubscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        phone: phoneNumber
                    })
                });

                hideLoading();

                if (response.ok || response.status === 200) {
                    // Show OTP modal
                    document.getElementById('otpModal').classList.remove('hidden');
                    // Focus first input
                    setTimeout(() => {
                        document.getElementById('otp1').focus();
                    }, 300);
                } else {
                    const data = await response.json();
                    const errorMsg = data.message || data.error || 'فشل إرسال رمز التحقق. يرجى المحاولة مرة أخرى.';
                    showError(errorMsg);
                }
            } catch (error) {
                hideLoading();
                showError('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
                console.error('OTP request error:', error);
            }
        }

        // Step 2: Confirm Unsubscribe with OTP
        async function confirmUnsubscribe() {
            const otp = getOtpValue();
            
            // Clear any previous errors
            document.getElementById('otpError').textContent = '';
            
            if (otp.length !== 4) {
                document.getElementById('otpError').textContent = 'يرجى إدخال رمز مكون من 4 أرقام';
                return;
            }

            if (!phoneNumber) {
                showError('رقم الهاتف غير متوفر');
                return;
            }

            showLoading('جاري إلغاء الاشتراك...');

            try {
                const response = await fetch('/api/client/unsubscribe-confirm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        phone: phoneNumber,
                        otp: otp
                    })
                });

                hideLoading();

                // If success (200 status)
                if (response.ok) {
                    const data = await response.json();
                    closeOtpModal();
                    showSuccess('تم الإلغاء بنجاح', 'تم إلغاء اشتراكك بنجاح');
                    // Wait 1.5 seconds then reload
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // If error (500 or any other error status)
                    // OTP modal is already visible, just show the error
                    clearOtpInputs();
                    document.getElementById('otpError').textContent = 'الرمز غير صحيح، يرجى المحاولة مرة أخرى';
                    document.getElementById('otp1').focus();
                }
            } catch (error) {
                hideLoading();
                // For network errors, show the error modal
                showError('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
                console.error('Unsubscribe confirm error:', error);
            }
        }

        // ==================== SUBSCRIBE/ACTIVATE Flow ====================
        
        // Step 1: Request OTP for Activation
        async function requestActivateOtp() {
            if (!phoneNumber) {
                showError('رقم الهاتف غير متوفر');
                return;
            }

            showLoading('جاري إرسال رمز التحقق...');

            try {
                const response = await fetch('/api/client/activate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        phone: phoneNumber
                    })
                });

                hideLoading();

                if (response.ok || response.status === 200) {
                    // Show OTP modal
                    document.getElementById('otpModal').classList.remove('hidden');
                    // Focus first input
                    setTimeout(() => {
                        document.getElementById('otp1').focus();
                    }, 300);
                } else {
                    const data = await response.json();
                    const errorMsg = data.message || data.error || 'فشل إرسال رمز التحقق. يرجى المحاولة مرة أخرى.';
                    showError(errorMsg);
                }
            } catch (error) {
                hideLoading();
                showError('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
                console.error('Activate OTP request error:', error);
            }
        }

        // Step 2: Confirm Activation with OTP
        async function confirmActivate() {
            const otp = getOtpValue();
            
            // Clear any previous errors
            document.getElementById('otpError').textContent = '';
            
            if (otp.length !== 4) {
                document.getElementById('otpError').textContent = 'يرجى إدخال رمز مكون من 4 أرقام';
                return;
            }

            if (!phoneNumber) {
                showError('رقم الهاتف غير متوفر');
                return;
            }

            showLoading('جاري تفعيل الاشتراك...');

            try {
                const response = await fetch('/api/client/activate-confirm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        phone: phoneNumber,
                        otp: otp
                    })
                });

                hideLoading();

                // If success (200 status)
                if (response.ok) {
                    const data = await response.json();
                    closeOtpModal();
                    showSuccess('تم التفعيل بنجاح', 'تم تفعيل اشتراكك بنجاح');
                    // Wait 1.5 seconds then reload
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // If error (500 or any other error status)
                    // OTP modal is already visible, just show the error
                    clearOtpInputs();
                    document.getElementById('otpError').textContent = 'الرمز غير صحيح، يرجى المحاولة مرة أخرى';
                    document.getElementById('otp1').focus();
                }
            } catch (error) {
                hideLoading();
                // For network errors, show the error modal
                showError('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
                console.error('Activate confirm error:', error);
            }
        }

        // ==================== Generic Confirm Action ====================
        
        // This function routes to the correct confirm function based on currentAction
        function confirmAction() {
            if (currentAction === 'subscribe') {
                confirmActivate();
            } else {
                confirmUnsubscribe();
            }
        }

        // ==================== Profile Update ====================
        
        async function handleProfileUpdate(event) {
            event.preventDefault();
            
            // Get form values
            const name = document.getElementById('editName').value.trim();
            const email = document.getElementById('editEmail').value.trim();
            
            // Clear previous errors
            document.getElementById('profileEditErrors').classList.add('hidden');
            document.getElementById('profileEditErrorsList').innerHTML = '';
            
            // Show loading
            showLoading('جاري تحديث البيانات...');
            
            try {
                const response = await fetch('/api/profile/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        name: name,
                        email: email
                    })
                });
                
                hideLoading();
                
                if (response.ok) {
                    // Success
                    closeEditProfileModal();
                    showSuccess('تم التحديث بنجاح', 'تم تحديث بياناتك بنجاح');
                    // Wait 1.5 seconds then reload
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Error response
                    const data = await response.json();
                    
                    // Check if there are validation errors
                    if (data.errors) {
                        // Display validation errors
                        let errorHtml = '<ul class="list-disc mr-5">';
                        for (const field in data.errors) {
                            if (data.errors.hasOwnProperty(field)) {
                                data.errors[field].forEach(error => {
                                    errorHtml += `<li>${error}</li>`;
                                });
                            }
                        }
                        errorHtml += '</ul>';
                        document.getElementById('profileEditErrorsList').innerHTML = errorHtml;
                        document.getElementById('profileEditErrors').classList.remove('hidden');
                    } else {
                        // Generic error
                        document.getElementById('profileEditErrorsList').innerHTML = 'حدث خطأ، حاول مرة أخرى';
                        document.getElementById('profileEditErrors').classList.remove('hidden');
                    }
                }
            } catch (error) {
                hideLoading();
                // Network error
                document.getElementById('profileEditErrorsList').innerHTML = 'حدث خطأ، حاول مرة أخرى';
                document.getElementById('profileEditErrors').classList.remove('hidden');
                console.error('Profile update error:', error);
            }
        }
    </script>
</body>
</html>

