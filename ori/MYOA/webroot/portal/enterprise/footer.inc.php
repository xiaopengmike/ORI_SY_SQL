        <!----- Î²²¿ ----->
        <div class="footer">
            <div style="font-family:arial;">Copyright &copy; <?=date('Y')?> <?=$obj_portal_data->get_unit_name()?> All rights reserved¡¡</div>
            <div><?=$obj_portal_data->get_miitbeian_no()?></div>
        </div>
    </div>
</div>
<script>
    if(window.external && typeof window.external.OA_SMS != 'undefined') 
    {       
        var h = Math.min(800, screen.availHeight - 100), 
        w = Math.min(1280, screen.availWidth - 180); 
        window.external.OA_SMS(w, h, "SET_SIZE"); 
    }
</script>
</body>
</html>