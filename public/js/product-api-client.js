/**
 * Product API Client - Example Usage
 *
 * File ini menunjukkan cara menggunakan API endpoints untuk CRUD Product dan Variant
 * Simpan token Sanctum setelah login user admin
 */

class ProductAPIClient {
    constructor(baseURL = "/api", token = null) {
        this.baseURL = baseURL;
        this.token = token;
    }

    setToken(token) {
        this.token = token;
    }

    getHeaders(includeAuth = true) {
        const headers = {
            Accept: "application/json",
        };

        if (includeAuth && this.token) {
            headers["Authorization"] = `Bearer ${this.token}`;
        }

        return headers;
    }

    async handleResponse(response) {
        const data = await response.json();

        if (!response.ok) {
            throw {
                status: response.status,
                data: data,
            };
        }

        return data;
    }

    // ===== PRODUCT ENDPOINTS =====

    /**
     * Get all products (public)
     */
    async getProducts(search = "", perPage = 15, page = 1) {
        const params = new URLSearchParams({
            search,
            per_page: perPage,
            page,
        });

        const response = await fetch(`${this.baseURL}/products?${params}`, {
            headers: this.getHeaders(false),
        });

        return this.handleResponse(response);
    }

    /**
     * Get single product with variants (public)
     */
    async getProduct(productId) {
        const response = await fetch(`${this.baseURL}/products/${productId}`, {
            headers: this.getHeaders(false),
        });

        return this.handleResponse(response);
    }

    /**
     * Create new product with variants (admin only)
     */
    async createProduct(formData) {
        // formData should be FormData object with:
        // - title: string
        // - description: string (optional)
        // - specification: string (optional)
        // - location: string
        // - image: File (optional)
        // - variants[0][name]: string
        // - variants[0][price]: number
        // - variants[0][stock]: number
        // - variants[0][image]: File (optional)

        const response = await fetch(`${this.baseURL}/admin/products`, {
            method: "POST",
            headers: this.getHeaders(true),
            body: formData,
        });

        return this.handleResponse(response);
    }

    /**
     * Get product for editing (admin only)
     */
    async getProductForEdit(productId) {
        const response = await fetch(
            `${this.baseURL}/admin/products/${productId}/edit`,
            {
                headers: this.getHeaders(true),
            },
        );

        return this.handleResponse(response);
    }

    /**
     * Update product (admin only)
     */
    async updateProduct(productId, formData) {
        // Add _method field untuk PUT request over form
        formData.append("_method", "PUT");

        const response = await fetch(
            `${this.baseURL}/admin/products/${productId}`,
            {
                method: "POST", // Form submission di browser hanya support GET/POST
                headers: this.getHeaders(true),
                body: formData,
            },
        );

        return this.handleResponse(response);
    }

    /**
     * Alternative: Update product using Fetch API native PUT
     */
    async updateProductPut(productId, formData) {
        const response = await fetch(
            `${this.baseURL}/admin/products/${productId}`,
            {
                method: "PUT",
                headers: {
                    Authorization: `Bearer ${this.token}`,
                    Accept: "application/json",
                },
                body: formData,
            },
        );

        return this.handleResponse(response);
    }

    /**
     * Delete product (admin only)
     */
    async deleteProduct(productId) {
        const response = await fetch(
            `${this.baseURL}/admin/products/${productId}`,
            {
                method: "DELETE",
                headers: this.getHeaders(true),
            },
        );

        return this.handleResponse(response);
    }

    // ===== VARIANT ENDPOINTS =====

    /**
     * Get all variants for product (admin only)
     */
    async getVariants(productId) {
        const response = await fetch(
            `${this.baseURL}/admin/products/${productId}/variants`,
            {
                headers: this.getHeaders(true),
            },
        );

        return this.handleResponse(response);
    }

    /**
     * Get single variant (admin only)
     */
    async getVariant(productId, variantId) {
        const response = await fetch(
            `${this.baseURL}/admin/products/${productId}/variants/${variantId}`,
            {
                headers: this.getHeaders(true),
            },
        );

        return this.handleResponse(response);
    }

    /**
     * Create variant (admin only)
     */
    async createVariant(productId, formData) {
        // formData should contain:
        // - name: string
        // - price: number
        // - stock: number
        // - image: File (optional)

        const response = await fetch(
            `${this.baseURL}/admin/products/${productId}/variants`,
            {
                method: "POST",
                headers: this.getHeaders(true),
                body: formData,
            },
        );

        return this.handleResponse(response);
    }

    /**
     * Update variant (admin only)
     */
    async updateVariant(productId, variantId, formData) {
        formData.append("_method", "PUT");

        const response = await fetch(
            `${this.baseURL}/admin/products/${productId}/variants/${variantId}`,
            {
                method: "POST",
                headers: this.getHeaders(true),
                body: formData,
            },
        );

        return this.handleResponse(response);
    }

    /**
     * Delete variant (admin only)
     */
    async deleteVariant(productId, variantId) {
        const response = await fetch(
            `${this.baseURL}/admin/products/${productId}/variants/${variantId}`,
            {
                method: "DELETE",
                headers: this.getHeaders(true),
            },
        );

        return this.handleResponse(response);
    }
}

// ===== USAGE EXAMPLES =====

/**
 * Example 1: Get all public products
 */
async function exampleGetProducts() {
    const client = new ProductAPIClient();

    try {
        const response = await client.getProducts("kaos", 10, 1);
        console.log("Products:", response.data);
        console.log("Pagination:", response.pagination);
    } catch (error) {
        console.error("Error:", error);
    }
}

/**
 * Example 2: Get single product with variants
 */
async function exampleGetProductWithVariants() {
    const client = new ProductAPIClient();

    try {
        const response = await client.getProduct(1);
        console.log("Product:", response.data);
        console.log("Variants:", response.data.variants);
    } catch (error) {
        console.error("Error:", error);
    }
}

/**
 * Example 3: Create product with variants (admin)
 */
async function exampleCreateProduct() {
    const client = new ProductAPIClient();
    client.setToken("your_sanctum_token_here");

    const formData = new FormData();

    // Product data
    formData.append("title", "Kaos Pria Premium");
    formData.append(
        "description",
        "Kaos berkualitas tinggi dengan material premium",
    );
    formData.append("specification", "Material: 100% Cotton\nTekstil: Jersey");
    formData.append("location", "Jakarta Selatan");

    // Product image
    const productImageInput = document.getElementById("productImage");
    if (productImageInput.files[0]) {
        formData.append("image", productImageInput.files[0]);
    }

    // Variants
    formData.append("variants[0][name]", "Size M");
    formData.append("variants[0][price]", 50000);
    formData.append("variants[0][stock]", 30);

    formData.append("variants[1][name]", "Size L");
    formData.append("variants[1][price]", 55000);
    formData.append("variants[1][stock]", 25);

    try {
        const response = await client.createProduct(formData);
        console.log("Product created:", response.data);
        console.log("New Product ID:", response.data.id);
    } catch (error) {
        console.error("Error:", error.data);
    }
}

/**
 * Example 4: Update product (admin)
 */
async function exampleUpdateProduct() {
    const client = new ProductAPIClient();
    client.setToken("your_sanctum_token_here");

    const formData = new FormData();

    // Updated product data
    formData.append("title", "Kaos Pria Premium v2");
    formData.append("description", "Updated description");
    formData.append(
        "specification",
        "Material: 100% Cotton\nTekstil: Jersey Premium",
    );
    formData.append("location", "Jakarta Pusat");

    // Existing variant (with id)
    formData.append("variants[0][id]", 1);
    formData.append("variants[0][name]", "Size M");
    formData.append("variants[0][price]", 60000);
    formData.append("variants[0][stock]", 20);

    // New variant (no id)
    formData.append("variants[1][name]", "Size XL");
    formData.append("variants[1][price]", 60000);
    formData.append("variants[1][stock]", 15);

    try {
        const response = await client.updateProductPut(1, formData); // Product ID = 1
        console.log("Product updated:", response.data);
    } catch (error) {
        console.error("Error:", error.data);
    }
}

/**
 * Example 5: Delete product (admin)
 */
async function exampleDeleteProduct() {
    const client = new ProductAPIClient();
    client.setToken("your_sanctum_token_here");

    try {
        const response = await client.deleteProduct(1); // Product ID = 1
        console.log("Product deleted:", response.message);
    } catch (error) {
        console.error("Error:", error.data);
    }
}

/**
 * Example 6: Manage variants (admin)
 */
async function exampleManageVariants() {
    const client = new ProductAPIClient();
    client.setToken("your_sanctum_token_here");

    const productId = 1;

    try {
        // Get all variants
        const variants = await client.getVariants(productId);
        console.log("All variants:", variants.data);

        // Create new variant
        const variantFormData = new FormData();
        variantFormData.append("name", "Size XXL");
        variantFormData.append("price", 65000);
        variantFormData.append("stock", 10);

        const createResponse = await client.createVariant(
            productId,
            variantFormData,
        );
        console.log("Variant created:", createResponse.data);

        // Update variant
        const updateFormData = new FormData();
        updateFormData.append("name", "Size XXL Updated");
        updateFormData.append("price", 70000);
        updateFormData.append("stock", 8);

        const updateResponse = await client.updateVariant(
            productId,
            createResponse.data.id,
            updateFormData,
        );
        console.log("Variant updated:", updateResponse.data);

        // Delete variant
        const deleteResponse = await client.deleteVariant(
            productId,
            createResponse.data.id,
        );
        console.log("Variant deleted:", deleteResponse.message);
    } catch (error) {
        console.error("Error:", error.data);
    }
}

/**
 * Example 7: Handle errors
 */
async function exampleErrorHandling() {
    const client = new ProductAPIClient();
    client.setToken("invalid_token");

    try {
        await client.getProductForEdit(999); // Non-existent product
    } catch (error) {
        if (error.status === 401) {
            console.log("Unauthorized - invalid token");
        } else if (error.status === 403) {
            console.log("Forbidden - not admin");
        } else if (error.status === 404) {
            console.log("Not found - product does not exist");
        } else if (error.status === 422) {
            console.log("Validation error:", error.data.errors);
        } else {
            console.log("Server error:", error.data);
        }
    }
}

// Export for use in other files
if (typeof module !== "undefined" && module.exports) {
    module.exports = ProductAPIClient;
}
