<div class="row text-center">
    <input id="btnPrint" type="button" class="btn btn-default print-btn" value="{{ $text ?? "Print this page" }}"
           onclick="printPage()"/>
</div>
<script type="text/javascript">
    function printPage() {
        var printButton = document.getElementById("btnPrint");
        printButton.style.visibility = 'hidden';
        window.print();
        printButton.style.visibility = 'visible';
    }
</script>