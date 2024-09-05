<script setup>
import { useRouter } from 'vue-router'
import { onMounted, ref, watch } from 'vue'

const router = useRouter()
let products = ref([])
let links = ref([])
let searchQuery = ref('')

onMounted(async () => {
    getProducts()
})

watch(searchQuery, () => {
    getProducts()
})
const newProduct = () => {
    router.push('/products/create')
}

const getProducts = async () => {
    let response = await axios.get('/api/products?&searchQuery=' + searchQuery.value)
        .then((response) => {
            products.value = response.data.products.data
            links.value = response.data.products.links
        })
}



const ourImage = (img) => {
    return "/upload/" + img
}
const changePage = (link) => {
    if (!link.url || link.active) {
        return
    }
    axios.get(link.url)
        .then((response) => {
            products.value = response.data.products.data
            links.value = response.data.products.links
        })
}

const onEdit = (id) => {
    router.push(`/products/${id}/edit`)
}

const deleteProduct = (id) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/products/${id}`)
                .then(() => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your product has been deleted.",
                        icon: "success"
                    });
                    getProducts()
                })

        }
    });
}
</script>

<template>
    <div class="products__list table  my-3">

        <div class="customers__titlebar dflex justify-content-between align-items-center">
            <div class="customers__titlebar--item">
                <h1 class="my-1">Products</h1>
            </div>
            <div class="customers__titlebar--item">
                <button @click="newProduct" class="btn btn-secondary my-1">
                    Create Product
                </button>
            </div>
        </div>
        <div class="table--heading  " style="padding-top: 20px;background:#FFF">
            <input type="search" placeholder="Search for a product"
                style="padding: 15px; font-size: 18px; border-radius: 10px; border: 1px solid #41B883;" name="search"
                v-model="searchQuery" />
        </div>
        <div class="table--heading mt-2 products__list__heading " style="padding-top: 20px;background:#FFF">

            <!-- <p class="table--heading--col1">&#32;</p> -->
            <p class="table--heading--col1">image</p>
            <p class="table--heading--col2">
                Product
            </p>
            <p class="table--heading--col4">Type</p>
            <p class="table--heading--col3">
                Inventory
            </p>
            <!-- <p class="table--heading--col5">&#32;</p> -->
            <p class="table--heading--col5">actions</p>
        </div>

        <!-- product 1 -->
        <div class="table--items products__list__item" v-for="product in products" :key="product.id">
            <div class="products__list__item--imgWrapper">
                <img class="products__list__item--img" :src="ourImage(product.image)" style="height: 40px;" />
            </div>
            <a href="# " class="table--items--col2">
                {{ product.name }}
            </a>
            <p class="table--items--col2">
                {{ product.type }}
            </p>
            <p class="table--items--col3">
                ${{ product.price }} - {{ product.quantity }} pcs
            </p>

            <div>
                <button @click="onEdit(product.id)" class="btn-icon btn-icon-success">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <button @click="deleteProduct(product.id)" class="btn-icon btn-icon-danger">
                    <i class="far fa-trash-alt"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="table-paginate">
        <div class="pagination">

            <a href="#" class="btn" v-for="(link, index) in links" :key="index" v-html="link.label"
                :class="{ active: link.active, disabled: !link.url }" @click="changePage(link)">
            </a>
        </div>
    </div>
</template>
