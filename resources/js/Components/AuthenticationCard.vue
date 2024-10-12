<template>
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 login-background">
    <div class="grid grid-cols-2 max-w-7xl shadow-md ">
        <div class=" hidden md:block ">
            <!-- <div class="flex h-[100%] w-[100%] bg-blue-600" ></div> -->
            <div class="h-[100%] bg-cover w-[600px]" style="background-image: url('images/login_logo.jpg') "></div>
        </div>
        <div class="bg-white col-span-2 mt-10 md:col-span-1 md:my-auto">
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