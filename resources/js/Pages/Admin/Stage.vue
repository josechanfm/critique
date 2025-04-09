<template>
<AdminLayout title="Dashboard">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{$t('config')}}
        </h2>
    </template>

    <div class="container mx-auto pt-5">
        <div class="bg-white relative shadow rounded-lg overflow-x-auto">
            <a-table :dataSource="users" :columns="columns" :pagination="{ pageSize:50  }"> 
                <template #bodyCell="{ column, text, record, index }">
                    <template v-if="column.dataIndex == 'operation'">
                        <a-button :href="route('admin.stages.viewTask', {'mission': mission.id, 'user_id':record.id } )">查看结果</a-button>
                    </template>
                    <template v-else>
                        {{ record[column.dataIndex] }}
                    </template>
                </template>
            </a-table>
        </div>
    </div>

</AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    notification
} from 'ant-design-vue';
import dayjs, { Dayjs }from 'dayjs';

import {
    defineComponent,
    reactive
} from "vue";
import * as AntdIcons from '@ant-design/icons-vue';

export default {
    components: {
        AdminLayout,
        notification,
        ...AntdIcons,
    },
    props: ["mission", "users"],
    data() {
        return {

            columns:[
                {
                    title: '用戶',
                    dataIndex: 'name',
                },{
                    title: '操作',
                    dataIndex: 'operation',
                    key: 'operation',
                },
            ],
        }
    }
}


</script>