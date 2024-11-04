<template>
<AdminLayout title="总览">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            总览
        </h2>
    </template>

    <div class="py-4">
        <div class="max-w-7xl mx-auto ">
            <a class="bg-white border shadow p-2 rounded" :href="route('dashboard')">← 返回</a>
            <div class="my-4">
                <a-table class="shadow-md" :columns="columns" :data-source="mission.stages" :pagination="{ pageSize: 20 }">
                    <template #bodyCell="{ column, text, record, index }">
                        <template v-if="column.dataIndex == 'tasks'">
                            <div v-for="task in record.tasks">
                                
                                <div v-if="task.title == '1'">
                                    <CheckOutlined class="text-green-500" /> 已完成
                                </div>
                                <div v-else-if="task.title == '0'">
                                    用戶未完成
                                </div>
                                <div v-else class="text-base py-1 ">
                                    <div class="w-[70%]">{{ task.title }}</div>
                                    <span class="font-bold">{{ task.content }}</span>
                                </div>
                            </div>
                        </template>
                        <template v-else-if="column.dataIndex == 'key'">
                            {{ index + 1 }}
                        </template>
                        <template v-else>
                            {{ record[column.dataIndex] }} 
                        </template>
                    </template>

                    <template #emptyText>
                        <div style="text-align: center; padding: 20px;">
                        暫時沒有回答
                        </div>
                    </template>
                </a-table>
            </div>

        </div>
    </div>
</AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import * as AntdIcons from '@ant-design/icons-vue';

export default {
    components: {
        AdminLayout,
        ...AntdIcons,
    },
    props: ["mission"],
    data() {
        return {
            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: "",
            },
            columns: [{
                title: "編號",
                dataIndex: "key",
            }, {
                title: "步驟",
                dataIndex: "title",
            }, {
                title: "填寫內容",
                dataIndex: "tasks",
            }, ],
        }
    }
}

</script>
