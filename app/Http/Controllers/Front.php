<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Post;
use App\Product;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Cart;

class Front extends Controller
{

    var $brands;
    var $categories;
    var $products;
    var $title;
    var $description;

    public function __construct()
    {
        $this->brands = Brand::all(array('name'));
        $this->categories = Category::all(array('name'));
        $this->products = Product::all(array('id', 'name', 'price'));
    }

    public function index()
    {
        return view('home', array('title' => 'Welcome', 'description' => '', 'page' => 'home', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function products()
    {
        return view('products', array('title' => 'Products Listing', 'description' => '', 'page' => 'products', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function product_details($id)
    {
        $product = Product::find($id);
        return view('product_details', array('product' => $product, 'title' => $product->name, 'description' => '', 'page' => 'products', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function product_categories($name)
    {
        return view('products', array('title' => 'Welcome', 'description' => '', 'page' => 'products', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function product_brands($name, $category = null)
    {
        return view('products', array('title' => 'Welcome', 'description' => '', 'page' => 'products', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function blog()
    {
        $posts = Post::where('id', '>', 0)->paginate(3);
        $posts->setPath('blog');

        $data['posts'] = $posts;

        return view('blog', array('data' => $data, 'title' => 'Latest Blog Posts', 'description' => '', 'page' => 'blog', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function blog_post($url)
    {
        $post = \App\Post::where('url', '=', $url)->get();

        $post_id = $post[0]['id'];
        $tags = \App\BlogPostTag::postTags($post_id);
        $previous_url = \App\Post::prevBlogPostUrl($post_id);
        $next_url = \App\Post::nextBlogPostUrl($post_id);

        $data['previous_url'] = $previous_url;
        $data['next_url'] = $next_url;
        $data['tags'] = $tags;
        $data['post'] = $post[0];

        return view('blog_post', array('data' => $data, 'title' => $post[0]['title'], 'description' => $post[0]['description'], 'page' => 'blog', 'brands' => $this->brands, 'categories' => $this->categories, 'products' => $this->products));
    }

    public function contact_us()
    {
        return view('contact_us', array('title' => 'Welcome', 'description' => '', 'page' => 'contact_us'));
    }

    public function cart()
    {
        if (Request::isMethod('post')) {
            $product_id = Request::get('product_id');
            $product = Product::find($product_id);
            Cart::add(array('id' => $product_id, 'name' => $product->name, 'qty' => 1, 'price' => $product->price));
        }

        //increment the quantity
        if (Request::get('product_id') && (Request::get('increment')) == 1) {
            $rowId = Cart::search(array('id' => Request::get('product_id')));
            $item = Cart::get($rowId[0]);

            Cart::update($rowId[0], $item->qty + 1);
        }

        //decrease the quantity
        if (Request::get('product_id') && (Request::get('decrease')) == 1) {
            $rowId = Cart::search(array('id' => Request::get('product_id')));
            $item = Cart::get($rowId[0]);

            Cart::update($rowId[0], $item->qty - 1);
        }

        //remove a item
        if (Request::get('product_id') && (Request::get('remove')) == 1) {
            $rowId = Cart::search(array('id' => Request::get('product_id')));
            if (!empty($rowId[0]))
                Cart::remove($rowId[0]);
            else
                return Redirect::away('cart');
        }

        $cart = Cart::content();

        return view('cart', array('cart' => $cart, 'title' => 'Welcome', 'description' => '', 'page' => 'home'));
    }

    public function cart_remove_item()
    {

    }

    public function clear_cart()
    {
        Cart::destroy();
        return Redirect::away('cart');
    }

    public function checkout()
    {
        return view('checkout', array('title' => 'Welcome', 'description' => '', 'page' => 'home'));
    }

    public function search($query)
    {
        return view('products', array('title' => 'Welcome', 'description' => '', 'page' => 'products'));
    }

    public function register()
    {
        if (Request::isMethod('post')) {
            User::create([
                'name' => Request::get('name'),
                'email' => Request::get('email'),
                'password' => bcrypt(Request::get('password')),
            ]);
        }
        return Redirect::away('login');
    }

    public function authenticate()
    {
        if (Auth::attempt(['email' => Request::get('email'), 'password' => Request::get('password')])) {
            return redirect()->intended('/');
        } else {
            return view('login', array('title' => 'Welcome', 'description' => '', 'page' => 'home'));
        }
    }

    public function login()
    {
        return view('login', array('title' => 'Welcome', 'description' => '', 'page' => 'home'));
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::away('login');
    }


}
