<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */
Route::get('/', 'Front@index');
Route::get('/products', 'Front@products');
Route::get('/products/details/{id}', 'Front@product_details');
Route::get('/products/categories/{name}', 'Front@product_categories');
Route::get('/products/brands/{name}/{category?}', 'Front@product_brands');
Route::get('/blog', 'Front@blog');
Route::get('/blog/{url}', 'Front@blog_post');
Route::get('/contact-us', 'Front@contact_us');

Route::get('/login', 'Front@login');
Route::get('/logout', 'Front@logout');

// Authentication routes...
Route::get('auth/login', 'Front@login');
Route::post('auth/login', 'Front@authenticate');
Route::get('auth/logout', 'Front@logout');

// Registration routes...
Route::post('/register', 'Front@register');

// Cart routes...
Route::get('/cart', 'Front@cart');
Route::post('/cart', 'Front@cart');
Route::post('/cart-remove-item', 'Front@cart_remove_item');
Route::get('/clear-cart', 'Front@clear_cart');
Route::get('/checkout', [
    'middleware' => 'auth',
    'uses' => 'Front@checkout'
]);


// API routes...
Route::get('/api/v1/products/{id?}', ['middleware' => 'auth.basic', function($id = null) {
    if ($id == null) {
        $products = App\Product::all(array('id', 'name', 'price'));
    } else {
        $products = App\Product::find($id, array('id', 'name', 'price'));
    }
    return Response::json(array(
        'error' => false,
        'products' => $products,
        'status_code' => 200
    ));
}]);

Route::get('/api/v1/categories/{id?}', ['middleware' => 'auth.basic', function($id = null) {
    if ($id == null) {
        $categories = App\Category::all(array('id', 'name'));
    } else {
        $categories = App\Category::find($id, array('id', 'name'));
    }
    return Response::json(array(
        'error' => false,
        'user' => $categories,
        'status_code' => 200
    ));
}]);

Route::get('/search/{query}', 'Front@search');

// Eloquent ORM
Route::get('/insert', function() {
    App\Category::create(array('name' => 'Music'));

    return 'category added';
});

Route::get('/read', function() {
    $category = new App\Category();

    $data = $category->all(array('name','id'));

    foreach ($data as $list) {
        echo $list->id . ' ' . $list->name . '<br>';
    }
});

Route::get('/update', function() {
    $category = App\Category::find(16);
    $category->name = 'HEAVY METAL';
    $category->save();

    $data = $category->all(array('name','id'));

    foreach ($data as $list) {
        echo $list->id . ' ' . $list->name . '<br>';
    }
});

Route::get('/delete', function() {
    $category = App\Category::find(5);
    $category->delete();

    $data = $category->all(array('name','id'));

    foreach ($data as $list) {
        echo $list->id . ' ' . $list->name . '<br>';
    }
});

Route::get('/raw', function () {
    $sql = "INSERT INTO categories (name) VALUES ('POMBE')";

    DB::statement($sql);
    $results = DB::select(DB::raw("SELECT * FROM categories"));

    print_r($results);
}
);

Route::get('blade', function () {
    $drinks = array('Vodka', 'Gin', 'Brandy');
    return view('page', array('name' => 'The Raven', 'day' => 'Friday', 'drinks' => $drinks));
});






