<template>
    <div class="mt-12">
        <v-card
             v-if="campaign.id"
             max-width="500"
        >
            <v-img
            class="align-end text-white"
            :src="
                campaign.image?  campaign.image : 'https://cdn.vuetifyjs.com/images/cards/docks.jpg'"
            cover
            >
            <v-card-title>{{ campaign.title }}</v-card-title>
            </v-img>

            <v-card-subtitle class="pt-4">
                <v-icon color="red">mdi-map-marker</v-icon> {{ campaign.address }}
            </v-card-subtitle>

            <v-card-text>
            <div max-width="500">{{ campaign.description }}</div>
            </v-card-text>

            <v-card-text>
                <div class="text-red text-h6">
                    Diperlukan : <v-icon>mdi-cash</v-icon> Rp. {{ campaign.required.toLocaleString('id-ID') }}
                </div>
                <div class="text-green text-h6">
                    Terkumpul : <v-icon>mdi-cash</v-icon> Rp. {{ campaign.colected.toLocaleString('id-ID') }}
                </div>
            </v-card-text>

            <v-card-actions>
            <v-btn color="text-secondary" block size="large" @click="donate" :disabled="campaign.colected >= campaign.required">
               <v-icon>mdi-heart</v-icon>DONATE
            </v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
    import { useUserStore } from '../store/user'
    export default {
        setup(){
            const useStore = useUserStore()
            return { useStore }
        },
        data(){
            return {
                campaign: {},
            }
        },
        created(){
            this.getCampaignDetail()
        },
        methods:{
            getCampaignDetail(){
                let {id} = this.$route.params
                let url = '/api/campaign/' + id
                
                axios.get(url).then((response) => {
                    this.campaign = response.data.data
                })

                .catch((error) => {
                    console.log(error)
                });
            },
            donate(){
                if (!useUserStore.isLogin) {
                    alert('Anda harus login dulu!')
                } else if(useUserStore.isNotVerifikasi == false){
                    alert('Anda belum terveririkasi!')
                } else {
                    alert('donate')
                }
            }
        }
    }
</script>