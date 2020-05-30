<?php

namespace App\Http\Controllers;

use App\Model\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Validator;  // Class ใช้ตรวจสอบข้อมูลในฟอร์ม
use Image;

//use Validator; // Class ใช้ตรวจสอบข้อมูลในฟอร์ม

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //อ่านข้อมูล
        $products = Product::paginate(4); // latest()->paginate(2) : เรียงจากล่าสุดก่อน
        return view('backend.pages.products.index', compact('products'))->with('i', (request()->input('page', 1) - 1) * 4);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pages.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo $request->input('product_name');
        // echo $request->product_barcode;
        /*
        echo "<pre>";
        print_r($request->all());
        echo "</pre>";
*/
        // ตรวจสอบ Validate
        $rules = [
            'product_name' => 'required',
            'product_barcode' => 'required|integer|digits:5|unique:products',
            'product_qty' => 'required',
            'product_price' => 'required',
            'product_category' => 'required'
        ];

        $messages = [
            'required' => 'ฟิลด์ :attribute นี้จำเป็น',
            'integer' => 'ฟิลด์นี้ต้องเป็นตัวเลขเท่านั้น',
            'digits' => 'ฟิลด์ :attribute ต้องเป็นตัวเลขความยาว :digits ตัวอักษร',

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) { //ตรวจสอบไม่ผ่าน
            return redirect()->back()->withErrors($validator)->withInput();
        } else {


            // Product::create($request->all()); // ถ้า validate ผ่านจะ insert ข้อมูลได้เลย (ชื่อต้องตรงกับ database ทำแบบนี้ได้เลย)

            $product_data = array(
                'product_name' => $request->product_name,
                'product_detail' => $request->product_detail,
                'product_barcode' => $request->product_barcode,
                'product_qty' => $request->product_qty,
                'product_price' => $request->product_price,
                'product_category' => $request->product_category,
                'product_status' => $request->product_status,
                'created_at' => now(),
                'updated_at' => now()
            );

            //Upload Product Image
            try {
                $image = $request->file('product_image');
                //เช็คว่ามีการเลือกไฟล์ภาพเข้ามาหรือไม่?
                if (!empty($image)) {
                    $file_name = "product_" . time() . rand(100, 999) . "." . $image->getClientOriginalExtension();
                    if ($image->getClientOriginalExtension() == "jpg" or $image->getClientOriginalExtension() == "png") {
                        $imgWidth = 300;
                        $folderUpload = "assets/images/products";
                        $path = $folderUpload . "/" . $file_name;

                        // Upload to folder products
                        $img = Image::make($image->getRealPath());

                        if ($img->width() > $imgWidth) {
                            $img->resize($imgWidth, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }

                        $img->save($path);
                        $product_data['product_image'] = $file_name; // update to db

                    } else {
                        return redirect()->route('products.create')
                            ->withErrors($validator)->withInput()
                            ->withInput()
                            ->with('status', '<div class="alert alert-danger">ไฟล์ภาพไม่รองรับ อนุญาติเฉพาะ .jpg และ .png</div>');
                    }
                }
            } catch (Exception $e) {
                print($e);
                return false;
            }

            $status = Product::create($product_data); // แบบ Maping  ระหว่าง field กับ input 


            return redirect()->route('products.create')->with('success', 'บันทึกข้อมูลเรียบร้อย'); // ส่งข้อความไปด้วย ที่หน้า create ด้วย ส่งด้วย Flat Session มาครั้งเดียว refresh ก็หายไป
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {

        return view('backend.pages.products.view', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product) // load form
    {
        return view('backend.pages.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Product $product) // update record
    {
        $product->update($request->except(['product_image']));

        $file_extension = $request->product_image->getClientOriginalExtension();
        $filename = $request->product_barcode . "." . $file_extension;

        Product::where('id', $product->id)->update(['product_image' => $filename]);

        if ($request->hasFile('product_image')) {
            $request->product_image->storeAs('products', $filename, 'upload');
        }

        return redirect()->route('products.index')->with('success', 'Delete product success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Delete product success');
    }
}
