<?php
include '../api/agentsPERcompagnes.php';
?>
<div class="card card-primary shadow-sm"><!--resizable-card-->
    <div class="card-header ">
        <h3 class="card-title">Utilisateurs par Compagnes</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div id="agentDonutChart" style="min-height: 250px;"></div>
    </div>
</div>