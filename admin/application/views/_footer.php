</div>
<div id="fullSetContent"></div>
<script type="text/javascript">
    $(function () {
        if (isExitsFunction(window.parent.fullSetButtonOut))
        {
            $("#fullSetContent").html(window.parent.fullSetButtonOut());
            $("#fullSetButton").click(function () {
                if($(this).attr("class") == "fa fa-expand"){
                    $(this).removeClass("fa-expand").addClass("fa-compress");
                } else {
                    $(this).removeClass("fa-compress").addClass("fa-expand");
                }
                window.parent.fullSet();
            });
        }
    });

</script>
</body>
</html>