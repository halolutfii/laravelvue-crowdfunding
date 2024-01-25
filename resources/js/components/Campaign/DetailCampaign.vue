<template>
        <v-card
            max-width="800"
        >
            <v-img
            class="align-end text-white"
            height="400"
            :src="
                campaignItem.image? imageURL + campaignItem.image : 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'
"
            cover
            >
            <v-card-title>{{ campaignItem.title }}</v-card-title>
            </v-img>

            <v-card-subtitle class="pt-4">
                <v-icon color="red">mdi-map-marker</v-icon> {{ campaignItem.address }}
            </v-card-subtitle>

            <v-card-text>
            <div>{{ campaignItem.description }}</div>
            </v-card-text>

            <v-card-actions>
            <v-btn color="red">
                diperlukan : <v-icon class="m-1">mdi-cash-multiple</v-icon> {{ campaignItem.required }}
            </v-btn>

            <v-btn color="green">
                terkumpul : <v-icon class="m-1">mdi-cash-multiple</v-icon> {{ campaignItem.colected }}
            </v-btn>
            </v-card-actions>
        </v-card>
</template>

<script>

export default{
    props: {
        campaignItem: Object,
    },
    data(){
        return {
            imageURL: 'http://localhost:8000',
        }
    }
}
</script>