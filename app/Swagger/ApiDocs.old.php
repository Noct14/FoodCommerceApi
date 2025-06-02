<?php

namespace App\Swagger;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API FoodCommerce",
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="JWT Bearer Token"
 * )
 *
 * @OA\Post(
 *     path="/api/register",
 *     summary="Register user baru",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password"},
 *             @OA\Property(property="name", type="string", example="TestSW"),
 *             @OA\Property(property="email", type="string", example="TestSW@email.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Register sukses"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validasi gagal"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/login",
 *     summary="Login user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", example="TestSW@email.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login sukses, token dikembalikan"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Login gagal"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/logout",
 *     summary="Logout user",
 *     tags={"Users"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logout sukses"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - Token invalid atau expired"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Tidak ada token aktif"
 *     )
 * )
 *
 *
 * @OA\Get(
 *     path="/api/products",
 *     summary="Lihat semua produk",
 *     tags={"Products"},
 *     @OA\Parameter(
 *            name="search",
 *            in="query",
 *            description="Kata kunci untuk mencari produk berdasarkan nama (case-insensitive)",
 *            required=false,
 *            @OA\Schema(type="string")
 *        ),
 *
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         required=false,
 *         description="Nomor halaman",
 *         @OA\Schema(
 *             type="integer",
 *             example=1
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         required=false,
 *         description="Jumlah produk per halaman",
 *         @OA\Schema(
 *             type="integer",
 *             example=10
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Daftar produk berhasil diambil",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="current_page",
 *                 type="integer",
 *                 example=1
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="product_name", type="string", example="Nasi Goreng"),
 *                     @OA\Property(property="price", type="integer", example=15000),
 *                     @OA\Property(property="category", type="string", example="Makanan"),
 *                     @OA\Property(property="description", type="string", example="Nasi goreng pedas"),
 *                     @OA\Property(property="stock", type="integer", example=10),
 *                     @OA\Property(property="image", type="string", example="nasi-goreng.jpg"),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-11T10:00:00"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-11T10:00:00")
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="first_page_url",
 *                 type="string",
 *                 example="http://localhost:8000/api/products?page=1"
 *             ),
 *             @OA\Property(
 *                 property="last_page",
 *                 type="integer",
 *                 example=5
 *             ),
 *             @OA\Property(
 *                 property="last_page_url",
 *                 type="string",
 *                 example="http://localhost:8000/api/products?page=5"
 *             ),
 *             @OA\Property(
 *                 property="next_page_url",
 *                 type="string",
 *                 example="http://localhost:8000/api/products?page=2"
 *             ),
 *             @OA\Property(
 *                 property="prev_page_url",
 *                 type="string",
 *                 example="null"
 *             ),
 *             @OA\Property(
 *                 property="per_page",
 *                 type="integer",
 *                 example=10
 *             ),
 *             @OA\Property(
 *                 property="total",
 *                 type="integer",
 *                 example=50
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Terjadi kesalahan server"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/products/{id}",
 *     summary="Lihat detail produk",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detail produk berhasil diambil"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Produk tidak ditemukan"
 *     )
 * )
 *
 *
 * @OA\Get(
 *     path="/api/products/seller/{sellerId}",
 *     summary="Lihat produk berdasarkan seller",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="sellerId",
 *         in="path",
 *         required=true,
 *         description="ID seller",
 *         @OA\Schema(
 *             type="integer",
 *             example=1
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         required=false,
 *         description="Nomor halaman",
 *         @OA\Schema(
 *             type="integer",
 *             example=1
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         required=false,
 *         description="Jumlah produk per halaman",
 *         @OA\Schema(
 *             type="integer",
 *             example=10
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Produk berdasarkan seller berhasil diambil",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="current_page",
 *                 type="integer",
 *                 example=1
 *             ),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="product_name", type="string", example="Nasi Goreng"),
 *                     @OA\Property(property="price", type="integer", example=15000),
 *                     @OA\Property(property="category", type="string", example="Makanan"),
 *                     @OA\Property(property="description", type="string", example="Nasi goreng pedas"),
 *                     @OA\Property(property="stock", type="integer", example=10),
 *                     @OA\Property(property="image", type="string", example="nasi-goreng.jpg"),
 *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-11T10:00:00"),
 *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-11T10:00:00")
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="first_page_url",
 *                 type="string",
 *                 example="http://localhost:8000/api/products/seller/1?page=1"
 *             ),
 *             @OA\Property(
 *                 property="last_page_url",
 *                 type="string",
 *                 example="http://localhost:8000/api/products/seller/1?page=5"
 *             ),
 *             @OA\Property(
 *                 property="next_page_url",
 *                 type="string",
 *                 example="http://localhost:8000/api/products/seller/1?page=2"
 *             ),
 *             @OA\Property(
 *                 property="prev_page_url",
 *                 type="string",
 *                 example="null"
 *             ),
 *             @OA\Property(
 *                 property="per_page",
 *                 type="integer",
 *                 example=10
 *             ),
 *             @OA\Property(
 *                 property="total",
 *                 type="integer",
 *                 example=50
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Seller tidak ditemukan"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/products",
 *     summary="Tambah produk baru",
 *     tags={"Products"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 required={"product_name","price","category"},
 *                 @OA\Property(property="product_name", type="string", example="Nasi Goreng"),
 *                 @OA\Property(property="price", type="integer", example=15000),
 *                 @OA\Property(property="category", type="string", example="Makanan"),
 *                 @OA\Property(property="description", type="string", example="Nasi goreng pedas banget"),
 *                 @OA\Property(property="stock", type="integer", example=20),
 *                 @OA\Property(property="image", type="string", example="nasi-goreng.jpg")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Produk berhasil ditambahkan"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Data yang dikirimkan ada yang salah"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/products/{id}",
 *     summary="Update produk",
 *     tags={"Products"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="product_name", type="string", example="Nasi Goreng Spesial"),
 *             @OA\Property(property="price", type="integer", example=18000)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Produk berhasil diupdate"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Produk tidak ditemukan"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/products/{id}",
 *     summary="Hapus produk",
 *     tags={"Products"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Produk berhasil dihapus"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Produk tidak ditemukan"
 *     )
 * )
 *
 */


class ApiDocs
{
    // ini cuma tempat anotasi, gak ada fungsi beneran
}
