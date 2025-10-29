<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdviceImageController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\EducationalContentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CharityWorkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EducationStageController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemDetailController;
use App\Http\Controllers\KidController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\MoralValueController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NumberController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostReportController;
use App\Http\Controllers\PrayerContentController;
use App\Http\Controllers\PrayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProphetStoryController;
use App\Http\Controllers\ProphetStoryDetailController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\WordGameController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConnexController;
use App\Http\Middleware\ConnexSubscribtionCheckMiddleware;


// Vistor
Route::prefix('auth')->group(function () {
    Route::post('/send-verification-code', [AuthController::class, 'sendVerificationCode']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('admin.login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Test endpoint (uses hardcoded user id = 5, no authentication required)

Route::get('client/request-protected-script', [ConnexController::class, 'protectedScript']);

Route::post('client/login', [ConnexController::class, 'login']);
Route::post('client/otp-confirm', [ConnexController::class, 'otpConfirm']);

Route::get('client/subscriber', [ConnexController::class, 'subscriber']);

Route::post('client/activate', [ConnexController::class, 'activate']);
Route::post('client/activate-confirm', [ConnexController::class, 'activateConfirm']);

Route::post('client/unsubscribe', [ConnexController::class, 'unsubscribe']);
Route::post('client/unsubscribe-confirm', [ConnexController::class, 'unsubscribeConfirm']);

Route::post('client/test-subscriber', [ConnexController::class, 'testSubscriber']);




// Authentacted User
Route::middleware(['auth:sanctum', ConnexSubscribtionCheckMiddleware::class])->group(function () {
    
    Route::get('/profile-view-test', [UserController::class, 'showProfileTest'])->withoutMiddleware(['auth:sanctum', ConnexSubscribtionCheckMiddleware::class]);
    Route::get('/profile-view', [UserController::class, 'showProfile'])->middleware('auth:sanctum');
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'profile']);
        Route::post('/update', [ProfileController::class, 'update']);
        Route::post('/update/phone', [ProfileController::class, 'updatePhone']);
    });
    Route::prefix('word_game')->group(function () {
        Route::get('/', [WordGameController::class, 'index']);
        Route::get('/random-word', [WordGameController::class, 'getRandomWord']);
    });
    Route::prefix('kids')->group(function () {
        Route::get('/', [KidController::class, 'index'])->name('kids.index');
        Route::get('/all', [KidController::class, 'getKids'])->name('kids.all');
        Route::post('/', [KidController::class, 'store'])->name('kids.store');
        Route::get('/{kid}', [KidController::class, 'show'])->name('kids.show');
        Route::put('/{kid}', [KidController::class, 'update'])->name('kids.update');
        Route::delete('/{kid}', [KidController::class, 'destroy'])->name('kids.destroy');
    });
    Route::prefix('advice')->group(function () {
        Route::get('/', [AdviceController::class, 'index'])->name('advice.index')->withoutMiddleware(['auth:sanctum', ConnexSubscribtionCheckMiddleware::class]);
        Route::post('/', [AdviceController::class, 'store'])->name('advice.store');
        Route::get('/{advice}', [AdviceController::class, 'show'])->name('advice.show');
        Route::put('/{advice}', [AdviceController::class, 'update'])->name('advice.update');
        Route::delete('/{advice}', [AdviceController::class, 'destroy'])->name('advice.destroy');
    });
    Route::prefix('letters')->group(function () {
        Route::get('/', [LetterController::class, 'index']);
        Route::get('/{letter}', [LetterController::class, 'show']);
    });
    Route::prefix('education-stages')->group(function () {
        Route::get('/', [EducationStageController::class, 'index'])
            ->name('education-stages.index')
            ->withoutMiddleware(['auth:sanctum', ConnexSubscribtionCheckMiddleware::class]);
    });
    Route::prefix('stories')->group(function () {
        Route::get('/', [StoryController::class, 'index'])->name('stories.index');
    });
    Route::prefix('charity-work')->group(function () {
        Route::get('/', [CharityWorkController::class, 'index'])->name('charity-work.index');
    });
    Route::prefix('moral-values')->group(function () {
        Route::get('/', [MoralValueController::class, 'index'])->name('moral-values.index');
    });

    Route::prefix('advice-image')->group(function () {
        Route::get('/', [AdviceImageController::class, 'index']);
    });
    Route::prefix('educational-contents')->group(function () {
        Route::get('/', [EducationalContentController::class, 'typeIndex'])
            ->name('educational-contents.index')
            ->withoutMiddleware(['auth:sanctum', ConnexSubscribtionCheckMiddleware::class]);
    });
    Route::prefix('numbers')->group(function () {
        Route::get('/', [NumberController::class, 'index']);
        Route::get('/{number}', [NumberController::class, 'show']);
    });
    Route::prefix('items')->group(function () {
        Route::get('/', [ItemController::class, 'categoryIndex'])->name('items.index'); // List all items
        Route::get('/{item}', [ItemController::class, 'show'])->name('items.show'); // Show a single item
    });
    Route::prefix('prophet-stories')->group(function () {
        Route::get('/', [ProphetStoryController::class, 'index'])->name('prophet-stories.index'); // List all stories
        Route::get('/{prophetStory}', [ProphetStoryController::class, 'show'])->name('prophet-stories.show'); // View a specific story
    });

    Route::prefix('prayers')->group(function () {
        Route::get('/', [PrayerController::class, 'index'])->name('prayers.index'); // List all stories
        Route::get('/{prayer}', [PrayerController::class, 'show'])->name('prayers.show'); // View a specific story
    });

    Route::prefix('posts')->group(function () {

        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        });

        Route::get('/', [PostController::class, 'index'])->name('posts.index'); // List all posts
        Route::post('/', [PostController::class, 'store'])->name('posts.store'); // Create a new post
        Route::get('/{post}', [PostController::class, 'show'])->name('posts.show'); // View a specific post
        Route::put('/{post}', [PostController::class, 'update'])->name('posts.update'); // Update a specific post
        Route::post('/{post}/toggle-like', [PostController::class, 'toggleLike']); // Update a specific post
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('admin.posts.destroy'); // Delete a specific post
        Route::post('/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store'); // Store a comment on a post
        Route::post('/{post}/report', [PostReportController::class, 'store']);
    });

    // get categories list



    Route::prefix('vaccinations')->group(function () {
        Route::get('/', [VaccinationController::class, 'index'])->name('vaccinations.index'); // List all vaccinations
        Route::get('/stats', [VaccinationController::class, 'stats'])->name('vaccinations.statistics'); // Get vaccination statistics
        Route::post('/', [VaccinationController::class, 'store'])->name('vaccinations.store'); // Create a new vaccination
        Route::get('/{vaccination}', [VaccinationController::class, 'show'])->name('vaccinations.show'); // Get a specific vaccination
        Route::put('/{vaccination}', [VaccinationController::class, 'update'])->name('vaccinations.update'); // Update a specific vaccination
        Route::delete('/{vaccination}', [VaccinationController::class, 'destroy'])->name('vaccinations.destroy'); // Delete a specific vaccination
    });

    Route::prefix('user')->group(function () { });

    Route::prefix('admin')->group(function () {


        Route::prefix('numbers')->group(function () {
            Route::post('/', [NumberController::class, 'store']);
            Route::put('/{number}', [NumberController::class, 'update']);
            Route::delete('/{number}', [NumberController::class, 'destroy']);
        });


        Route::prefix('notifications')->group(function () {
            Route::post('/send', [NotificationController::class, 'send']);
        });

        Route::prefix('letters')->group(function () {
            Route::post('/', [LetterController::class, 'store']);
            Route::put('/{letter}', [LetterController::class, 'update']);
            Route::delete('/{letter}', [LetterController::class, 'destroy']);
        });
        Route::prefix('prayers')->group(function () {

            Route::post('/', [PrayerController::class, 'store'])->name('prayers.store');
            Route::put('/{prayer}', [PrayerController::class, 'update'])->name('prayers.update');
            Route::delete('/{prayer}', [PrayerController::class, 'destroy'])->name('prayers.destroy');
        });
        Route::prefix('prayers-content')->group(function () {
            Route::get('/', [PrayerContentController::class, 'index']);
            Route::post('/', [PrayerContentController::class, 'store']);
            Route::put('/{prayerContent}', [PrayerContentController::class, 'update']);
            Route::delete('/{prayerContent}', [PrayerContentController::class, 'destroy']);
        });
        Route::prefix('advice-image')->group(function () {
            Route::post('/', [AdviceImageController::class, 'store']);
        });
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
        });

        Route::prefix('guardians')->group(function () {
            Route::get('/', [GuardianController::class, 'index'])->name('guardians.index'); // List all guardians
            Route::get('/all', [GuardianController::class, 'getGuardians']); // List all guardians
            Route::post('/', [GuardianController::class, 'store'])->name('guardians.store'); // Create a new guardian
            Route::get('/{guardian}', [GuardianController::class, 'show'])->name('guardians.show'); // Get a specific guardian
            Route::put('/{guardian}', [GuardianController::class, 'update'])->name('guardians.update'); // Update a specific guardian
            Route::delete('/{guardian}', [GuardianController::class, 'destroy'])->name('guardians.destroy'); // Delete a specific guardian
        });

        // Guardian CRUD routes
        Route::prefix('admins')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('admins.index'); // List all guardians
            Route::post('/', [AdminController::class, 'store'])->name('admins.store'); // Create a new guardian
            Route::get('/{admin}', [AdminController::class, 'show'])->name('admins.show'); // Get a specific guardian
            Route::put('/{admin}', [AdminController::class, 'update'])->name('admins.update'); // Update a specific guardian
            Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy'); // Delete a specific guardian
        });

        Route::prefix('items')->group(function () {
            Route::get('', [ItemController::class, 'index'])->name('items.index'); // Create a new item
            Route::post('/', [ItemController::class, 'store'])->name('items.store'); // Create a new item
            Route::put('/{item}', [ItemController::class, 'update'])->name('items.update'); // Update an item
            Route::delete('/{item}', [ItemController::class, 'destroy'])->name('items.destroy'); // Delete an item
        });
        Route::prefix('item-details')->group(function () {
            Route::post('/', [ItemDetailController::class, 'store']);
            Route::put('/{id}', [ItemDetailController::class, 'update']);
            Route::delete('/{id}', [ItemDetailController::class, 'destroy']);
        });
        Route::prefix('prophet-stories')->group(function () {
            Route::post('/', [ProphetStoryController::class, 'store'])->name('prophet-stories.store'); // Create a new story
            Route::put('/{prophetStory}', [ProphetStoryController::class, 'update'])->name('prophet-stories.update'); // Update a specific story
            Route::delete('/{prophetStory}', [ProphetStoryController::class, 'destroy'])->name('prophet-stories.destroy'); // Delete a story
        });
        Route::prefix('prophet-story-detail')->group(function () {
            Route::post('/', [ProphetStoryDetailController::class, 'store'])->name('prophet-stories.store'); // Create a new story
            Route::put('/{id}', [ProphetStoryDetailController::class, 'update'])->name('prophet-stories.update'); // Update a specific story
            Route::delete('/{id}', [ProphetStoryDetailController::class, 'destroy'])->name('prophet-stories.destroy'); // Delete a story
        });

        Route::prefix('education-stages')->group(function () {
            Route::put('/{educationStage}', [EducationStageController::class, 'update'])->name('education-stages.update');
        });

        Route::prefix('educational-contents')->group(function () {
            Route::get('/', [EducationalContentController::class, 'index'])->name('educational-contents.index');
            Route::post('/', [EducationalContentController::class, 'store'])->name('educational-contents.store');
            Route::get('/{educationalContent}', [EducationalContentController::class, 'show'])->name('educational-contents.show');
            Route::put('/{educationalContent}', [EducationalContentController::class, 'update'])->name('educational-contents.update');
            Route::delete('/{educationalContent}', [EducationalContentController::class, 'destroy'])->name('educational-contents.destroy');
        });
        Route::prefix('stories')->group(function () {
            Route::get('/', [StoryController::class, 'index'])->name('stories.index');
            Route::post('/', [StoryController::class, 'store'])->name('stories.store');
            Route::get('/{story}', [StoryController::class, 'show'])->name('stories.show');
            Route::put('/{story}', [StoryController::class, 'update'])->name('stories.update');
            Route::delete('/{story}', [StoryController::class, 'destroy'])->name('stories.destroy');
        });
        Route::prefix('charity-works')->group(function () {
            Route::get('/', [CharityWorkController::class, 'index'])->name('charity-works.index');
            Route::post('/', [CharityWorkController::class, 'store'])->name('charity-works.store');
            Route::get('/{charityWork}', [CharityWorkController::class, 'show'])->name('charity-works.show');
            Route::put('/{charityWork}', [CharityWorkController::class, 'update'])->name('charity-works.update');
            Route::delete('/{charityWork}', [CharityWorkController::class, 'destroy'])->name('charity-works.destroy');
        });
        Route::prefix('moral-values')->group(function () {
            Route::get('/', [MoralValueController::class, 'index'])->name('moral-values.index');
            Route::post('/', [MoralValueController::class, 'store'])->name('moral-values.store');
            Route::get('/{moralValue}', [MoralValueController::class, 'show'])->name('moral-values.show');
            Route::put('/{moralValue}', [MoralValueController::class, 'update'])->name('moral-values.update');
            Route::delete('/{moralValue}', [MoralValueController::class, 'destroy'])->name('moral-values.destroy');
        });
    });
});
