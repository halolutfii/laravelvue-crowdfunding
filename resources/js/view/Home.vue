<template>
    <div class="container mt-12">
        <v-container class="bg-white">
            <v-row no-gutter>
                <v-col v-for="campaign in campaign" :key="'campaign' + campaign.id" cols="12" sm="6" class="mt-2">
                    <campaign-item :campaign="campaign"></campaign-item>
                </v-col>
            </v-row>
        </v-container>
        <v-pagination v-model="page" :length="lengthPage" @click="getCampaigns"></v-pagination>
    </div>
</template>

<script>
import CampaignItem from '../components/CampaignItem.vue'
export default{
    data(){
        return {
            page: 1,
            lengthPage: 0,
            campaign: [],
        }
    },
    components: {
        CampaignItem,
    },
    created(){
        this.getCampaigns();
    },
    methods: {
        getCampaigns(){
            let url = `/api/getallcampaign?page=` + this.page;

            axios
            .get(url).then((response) => {
                let {data} = response.data;
                this.campaign = data.campaign.data;
                this.lengthPage = data.campaign.last_page;
            })

            .catch((error) => {
                console.log(error);
            });
        }
    }
}
</script>