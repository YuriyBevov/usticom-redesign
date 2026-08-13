<div class="loader">
    <div class="lds-ring">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>

    <div class="lds-inner-ring">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    
</div>

<style>
    .loader {
        display: none;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
        
        width: 100vw;
        height: 100vh;

        background-color: rgba(0,0,0,.5);
        z-index: 1001;
    }
    .loader.active {
        display: flex;
    }

    .lds-ring,
    .lds-inner-ring {
        display: inline-block;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
    }
    .lds-ring div,
    .lds-inner-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 64px;
        height: 64px;
        margin: 8px;
        border: 8px solid #bdbdbd;
        border-radius: 50%;
        animation: lds-ring 1.4s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #bdbdbd transparent transparent transparent;
    }
    .lds-ring div:nth-child(1),
    .lds-inner-ring div:nth-child(1) {
        animation-delay: -0.45s;
    }
    .lds-ring div:nth-child(2),
    .lds-inner-ring div:nth-child(2) {
        animation-delay: -0.3s;
    }
    .lds-ring div:nth-child(3),
    .lds-inner-ring div:nth-child(3) {
        animation-delay: -0.15s;
    }

    /*inner*/
    .lds-inner-ring {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .lds-inner-ring div {
        width: 40px;
        height: 40px;
        border: 5px solid #a70a12;
        animation: lds-inner-ring 2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #a70a12 transparent transparent transparent;
    }

    @keyframes lds-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    @keyframes lds-inner-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(-360deg);
        }
    }
</style>

<script>
    BX.showWait = function () { 
        document.querySelector('.loader').classList.add('active');
    };

    BX.closeWait = function () { 
        document.querySelector('.loader').classList.remove('active');
    };
</script>