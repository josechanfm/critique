<template>
<AdminLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            阅读
        </h2>
    </template>

    <div class="w-[1000px] mx-auto my-4 " v-if="!readingFinish">
        <!-- 引导页 -->
        <div v-if="currentStage === 'guide'" class="guide flex flex-col gap-4">
            <div class="text-lg">阅读指引</div>
            <div class="text-base py-2">请仔细阅读以下三类资料，系统将记录您的阅读时间</div>
            <button @click="startReading" class="bg-blue-600 hover:bg-blue-700 w-32">开始阅读</button>
        </div>

        <!-- 文章阅读页 -->
        <div v-else class="rounded-lg shadow bg-white p-2">
            <div v-if="currentArticleIndex == 0">
                <div class="text-center text-base font-bold">{{ currentArticle.title }}</div>
                <div class="grid md:grid-cols-2 grid-cols-1 gap-4 md:p-5 p-4">
                    <div class="px-4 py-2 shadow-md border rounded-lg bg-slate-100/50">
                        <div class="min-h-36 ">
                            <div class="mx-4 my-2 text-lg font-bold">{{$t('video_title')}}</div>
                        </div>
                    </div>
                    <div class="px-4 py-2 shadow-md border rounded-lg bg-slate-100/50">
                        <div class="min-h-40 ">
                            <div class="mx-4 my-2 text-lg font-bold">{{$t('file_title')}}</div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="currentArticleIndex == 1">
                <div class="text-center text-base font-bold">{{ currentArticle.title }}</div>
                <div class="grid md:grid-cols-2 grid-cols-1 gap-4 md:p-5 p-4">
                    <div class="px-4 py-2 shadow-md border rounded-lg bg-slate-100/50">
                        <div class="min-h-36 ">
                            <div class="mx-4 my-2 text-lg font-bold">{{$t('video_title')}}</div>
                        </div>
                    </div>
                    <div class="px-4 py-2 shadow-md border rounded-lg bg-slate-100/50">
                        <div class="min-h-40 ">
                            <div class="mx-4 my-2 text-lg font-bold">{{$t('file_title')}}</div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="currentArticleIndex == 2">
                <div class="text-center text-base font-bold">{{ currentArticle.title }}</div>
                <div class="grid md:grid-cols-2 grid-cols-1 gap-4 md:p-5 p-4">
                    <div class="px-4 py-2 shadow-md border rounded-lg bg-slate-100/50">
                        <div class="min-h-36 ">
                            <div class="mx-4 my-2 text-lg font-bold">{{$t('video_title')}}</div>
                        </div>
                    </div>
                    <div class="px-4 py-2 shadow-md border rounded-lg bg-slate-100/50">
                        <div class="min-h-40 ">
                            <div class="mx-4 my-2 text-lg font-bold">{{$t('file_title')}}</div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <button v-if="currentArticleIndex < articles.length - 1" @click="nextArticle" class="mx-4 mb-4 next-button bg-lime-600 hover:bg-lime-700">
                下一类
            </button>
            <button v-else @click="finishReading" class="mx-4 mb-4 bg-amber-600 hover:bg-amber-700">
                完成阅读
            </button>
        </div>
    </div>
    <div class="w-[1000px] mx-auto my-4 " v-else>
        <div class="rounded-lg shadow bg-white p-10">
            <div v-if="displayMessage(0)">推荐您练习的经济类1-5的任务话题。</div>
            <div v-if="displayMessage(1)">推荐您练习社会类6-10的任务话题。</div>
            <div v-if="displayMessage(2)">推荐您练习环境类11-15的任务话题。</div>
        </div>
    </div>
</AdminLayout>
</template>

  
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
export default {
    
    components: {
        AdminLayout,
    },
    data() {
        return {
            currentStage: 'guide', // 'guide' | 'reading' | 'finished'
            currentArticleIndex: 0,
            readingTimes: [0, 0, 0], // 每篇文章的阅读时间(秒)
            timer: null,
            startTime: null,

            articles: [{
                    id: 1,
                    title: "经济类",
                    content: "【澳門日報消息】因應電子政務和市場服務的快速發展，社會工作局組織長者、康復、家庭和社區服務範疇的80多間社服設施，在今年3月至12月期間開展智能手機應用培訓活動，協助長者、殘疾及有需要人士提升應用智能手機的能力，從而提升生活便利。 <br><br> 上述培訓的內容包括：“一戶通”、“我的健康2.0”、電子支付、數碼點餐，以及其他常用的手機應用程式等教學。如欲查詢活動詳情，可瀏覽澳門特區長者服務資訊網及澳門特區康復服務資訊網。"
                },
                {
                    id: 2,
                    title: "社会类",
                    content: "【澳門日報消息】由發現澳門科技有限公司主辦的“星耀澳琴計劃”，目標是“年度30場主題演藝活動”，首場演出將於4月27日晚上8時於橫琴創新方動融館開啟，由香港歌手JW王灝兒領銜獻唱。<br><br>主辦方今日下午3時在橫琴創新方舉行新聞發佈會介紹相關計劃，該計劃以推動粵港澳大灣區文化融合為核心目標，以“年度30場主題演藝活動”為載體，通過音樂表演、脫口秀等多元化表演形式，匯聚粵港澳三地優秀藝術家及演藝人才，深化人才、作品與市場的深度對接，為大灣區演藝生態圈建設注入活力。<br><br>深合區經濟發展局局長吳創偉在會上表示，發現澳門推出“星耀澳琴計劃”，通過“演藝+”的深度融合模式，進一步豐富澳琴的文旅體驗，有助推動粵港澳三地演藝文化資源互動互聯"
                },
                {
                    id: 3,
                    title: "环境类",
                    content: "【澳門日報消息】氣象局今日下午1時30分發佈特別推送信息指出，受一股潮濕的海洋性氣流影響，本澳天氣將再次進入“回南天”。今日起，將開始出現薄霧或霧，天氣潮濕。而隨著周末一道鋒面“報到”，天氣將出現明顯的轉變，一齊睇睇天氣走勢。<br><b>“回南天”潮濕有霧</b><br>隨著高壓的遠離，一股海洋性氣流將於本周中至後期為廣東沿岸帶來潮濕多霧的天氣，期間將有幾陣驟雨（圖1）。與此同時，本澳氣溫亦將逐步上升，預料日間最高氣溫將升至27度左右。由於“回南天”容易導致地面濕滑及能見度降低，就此，提醒市民留意路面情況，且駕車人士需特別注意，同時市民亦可關好門窗減少濕氣進入室內，並適時使用抽濕設備，以保持環境乾爽。"
                }
            ],

            readingFinish:false,
        }
    },
    computed: {
        currentArticle() {
            return this.articles[this.currentArticleIndex];
        }
    },
    methods: {
        startReading() {
            this.currentStage = 'reading';
            this.startTimer();
        },

        nextArticle() {
            // 停止当前文章的计时
            this.stopTimer();

            // 记录当前文章阅读时间
            this.readingTimes[this.currentArticleIndex] = this.getElapsedTime();

            // 转到下一篇文章
            this.currentArticleIndex++;

            // 开始新文章的计时
            this.startTimer();
        },

        finishReading() {
            this.stopTimer();
            this.readingTimes[this.currentArticleIndex] = this.getElapsedTime();
            this.currentStage = 'finished';

            // 输出阅读时间数据
            console.log('阅读完成，各篇文章用时：', this.readingTimes);
            
            this.readingFinish = true
        },

        startTimer() {
            this.startTime = new Date();
            this.timer = setInterval(() => {
                const elapsed = this.getElapsedTime();
                this.readingTimes[this.currentArticleIndex] = elapsed;
            }, 1000);
        },

        stopTimer() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
        },

        getElapsedTime() {
            return Math.floor((new Date() - this.startTime) / 1000);
        },

        formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins}分${secs}秒`;
        },
        displayMessage(number){
            // 最高的數字是哪一個Key
            let maxNumber = this.readingTimes.indexOf( Math.max(...this.readingTimes) )
            return number == maxNumber
        }
    },
    beforeDestroy() {
        this.stopTimer();
    }
}
</script>

  
<style scoped>

.guide,
.article-container {
    background: #f9f9f9;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.article-content {
    margin: 20px 0;
    line-height: 1.6;
}

.timer {
    margin: 15px 0;
    color: #666;
    font-size: 14px;
}

button {
    padding: 10px 20px;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}


</style>
