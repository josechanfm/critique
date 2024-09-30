<template>
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 login-background">
    <div class="grid grid-cols-2 max-w-7xl">
        <div >
            <!-- <div class="flex h-[100%] w-[100%] bg-blue-600" ></div> -->
            <div class="h-[100%] bg-cover w-[700px]" style="background-image: url('login-background.jpg') "></div>
        </div>
        <div class="border-2 bg-white">
            <div class="flex justify-center">
                <slot name="logo" />
            </div>

            <div class="w-full  mt-6 px-6 py-4 bg-white shadow-md overflow-hidden ">
                <slot />
            </div>
        </div>
    </div>
</div>
</template>

<style>
.login-background{
    background: rgb(214,255,219);
    background: linear-gradient(135deg, rgba(214,255,219,0.5746673669467788) 0%, rgba(227,255,229,0.5830707282913166) 25%, rgba(214,255,215,0.5382528011204482) 60%, rgba(189,244,255,1) 100%);
}
</style>