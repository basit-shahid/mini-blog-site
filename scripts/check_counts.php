<?php
$autoload = __DIR__ . '/../vendor/autoload.php';
if (! file_exists($autoload)) {
	echo "vendor/autoload.php not found, run composer install\n";
	exit(1);
}
require $autoload;
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;

echo "Posts: " . Post::count() . PHP_EOL;
echo "Users: " . User::count() . PHP_EOL;
echo "Categories: " . Category::count() . PHP_EOL;
echo "Tags: " . Tag::count() . PHP_EOL;
