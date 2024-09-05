<script setup>
import { useRouter, useRoute } from 'vue-router'
import { onMounted, reactive, ref } from 'vue'

let errors = ref([])
const editMode = ref(false)

const router = useRouter()
const route = useRoute()
const form = reactive({
    name: "",
    description: "",
    image: "",
    type: "",
    quantity: "",
    price: "",

})

onMounted(() => {
    if (route.name === 'products.edit') {
        editMode.value = true
        getProduct()
    }
})
const getProduct = async () => {
    let response = await axios.get(`/api/products/${route.params.id}/edit`)
        .then((response) => {
            form.name = response.data.product.name
            form.description = response.data.product.description
            form.image = response.data.product.image
            form.type = response.data.product.type
            form.quantity = response.data.product.quantity
            form.price = response.data.product.price
        })
}
const homePage = () => {
    router.push('/')
}
const handleSave = (values, actions) => {
    if (editMode.value) {
        updateProduct(values, actions)
    } else {
        createProduct(values, actions)
    }
}

const createProduct = (values, actions) => {
    axios.post('/api/products', form)
        .then((response) => {
            router.push('/')
            toast.fire({ icon: "success", title: "Product Added Successfully!" })
        })
        .catch((error) => {
            if (error.response.status === 422) {
                errors.value = error.response.data.errors
            }
        })
}

const updateProduct = (values, actions) => {
    axios.put(`/api/products/${route.params.id}`, form)
        .then((response) => {
            router.push('/')
            toast.fire({ icon: "success", title: "Product Updated Successfully!" })
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                // Handle validation errors
                errors.value = {};
                for (const [field, messages] of Object.entries(error.response.data.errors)) {
                    errors.value[field] = messages.join(', '); // Join messages with a comma
                }
                toast.fire({ icon: "error", title: "Please correct the errors in the form." });
            } else {
                // Log other types of errors if necessary
                console.error("An unexpected error occurred:", error);
            }
        })
}


const getImage = () => {
    let image = "/upload/no-image.png"
    if (form.image) {
        if (form.image.indexOf("base64") != -1) {
            image = form.image
        } else {
            image = "/upload/" + form.image
        }
    }

    return image
}

const handleFileChange = (e) => {
    let file = e.target.files[0]
    let reader = new FileReader()
    reader.onloadend = (file) => {
        form.image = reader.result
    }
    reader.readAsDataURL(file)
}


</script>
<template>
    <div class="products__create ">

        <div class="products__create__titlebar dflex justify-content-between align-items-center">
            <div class="products__create__titlebar--item">

                <h1 class="my-1">
                    <span v-if="editMode">Edit</span>
                    <span v-else>Create</span>

                    Product
                </h1>
            </div>
            <div class="products__create__titlebar--item">

                <button @click="homePage" class="btn btn-secondary my-1">
                    Return
                </button>
            </div>
        </div>

        <div class="products__create__cardWrapper mt-2">
            <div class="products__create__main">
                <div class="products__create__main--addInfo card py-2 px-2 bg-white">
                    <p class="mb-1">Name</p>
                    <input name="name" type="text" class="input" v-model="form.name" autocomplete="false">
                    <small style="color: red;" v-if="errors.name">{{ errors.name }}</small>
                    <p class="my-1">Description (optional)</p>
                    <textarea name="description" cols="10" rows="5" class="textarea"
                        v-model="form.description"></textarea>
                    <small style="color: red;" v-if="errors.description">{{ errors.description }}</small>
                    <div class="products__create__main--media--images mt-2">
                        <ul class="products__create__main--media--images--list list-unstyled">
                            <li class="products__create__main--media--images--item">
                                <div class="products__create__main--media--images--item--imgWrapper">
                                    <img :src="getImage()" class="products__create__main--media--images--item--img"
                                        alt="product-image" />
                                </div>
                            </li>
                            <!-- upload image small -->
                            <li class="products__create__main--media--images--item">
                                <form class="products__create__main--media--images--item--form">
                                    <label class="products__create__main--media--images--item--form--label"
                                        for="myfile">Add Image</label>
                                    <input name="image" @change="handleFileChange"
                                        class="products__create__main--media--images--item--form--input" type="file"
                                        id="myfile" />
                                </form>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
            <div class="products__create__sidebar">
                <!-- Product Organization -->
                <div class="card py-2 px-2 bg-white">

                    <!-- Product unit -->
                    <div class="my-3">
                        <p>Product type</p>
                        <input name="type" type="text" class="input" v-model="form.type">
                    </div>
                    <hr>

                    <!-- Product invrntory -->
                    <div class="my-3">
                        <p>Inventory</p>
                        <input name="quantity" type="text" class="input" v-model="form.quantity">
                    </div>
                    <hr>

                    <!-- Product Price -->
                    <div class="my-3">
                        <p>Price</p>
                        <input name="price" type="text" class="input" v-model="form.price">
                    </div>
                </div>

            </div>
        </div>
        <!-- Footer Bar -->
        <div class="dflex justify-content-between align-items-center my-3">
            <p></p>
            <button @click="handleSave" class="btn btn-secondary">Save</button>
        </div>

    </div>
</template>