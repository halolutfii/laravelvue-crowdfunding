import { createRouter, createWebHistory } from "vue-router";
import { useUserStore } from "./store/user";

const Home = () => import('@/view/Home.vue') 
const Campaign = () => import('@/view/Campaign.vue') 
const Verification = () => import('@/view/Verification.vue') 
const DetailCampaign = () => import('@/view/DetailCampaign.vue') 

const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHistory(),
    routes:[
        {
            path : '/',
            name : 'home',
            component : Home
        },
        {
            path : '/campaign',
            name : 'campaign',
            component : Campaign,
            meta: {
                requiredAdmin: true 
            }
        },
        {
            path : '/campaign/:id',
            name : 'campaignDetail',
            component : DetailCampaign,
        },
        {
            path : '/verification',
            name : 'verification',
            component : Verification,
            meta: {
                requiredVeri: true
            }
        },
        {
            path: '/:catchAll(.*)',
            redirect: '/'
        },
    ]
     // short for `routes: routes`
  })

  router.beforeEach((to, from) => {
    const auth = useUserStore()
    if(to.meta.requiredVeri){
        if(!auth.isNotVerification){
            alert("Maaf, anda tidak memiliki akses di halaman ini!")
            return {
                path : '/'
            }
        }
    }
    if(to.meta.requiredAdmin){
        if(!auth.isAdmin){
            alert("Maaf, hanya admin memiliki akses di halaman ini!")
            return {
                path : '/'
            }
        }
    }
  })

  export default router;