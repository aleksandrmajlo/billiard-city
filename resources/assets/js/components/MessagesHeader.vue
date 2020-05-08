<template>
    <li class="dropdown messages-menu hidden-xs">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="/img/messeg.png" alt="messeg">
            <span class="label">0</span>
        </a>

        <ul class="dropdown-menu" v-if="isShow">
            <li class="header">У вас 0 уведомлений</li>
            <li>
                <ul class="menu">
                </ul>
            </li>
            <li class="footer"><a href="#">See All Messages</a></li>
        </ul>
    </li>
</template>

<script>
    export default {
        name: "MessagesHeader",
        data() {
            return {
                isShow: false
            }
        }
    }
</script>
