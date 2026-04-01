<div class="card card-primary shadow-sm">
    <div class="card-header d-flex align-items-center">
        <div class="card-tools" style="display: flex;justify-content: space-between;width: 100%;align-items: center;">
            <h3 class="card-title mb-0">Cumuls Utilisateurs</h3>
            <div class="d-flex align-items-center" style="gap: 10px;">
                <input type="month" id="chartPeriod"
                    class="form-control form-control-sm border-0"
                    style="background: rgba(255,255,255,0.2); color: white; width: auto;"
                    onchange="loadAndInitChart()">
                <div class="dropdown">
                    <button class="btn btn-tool dropdown-toggle" type="button" id="filterDropdown" data-toggle="dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        style="background: rgba(255,255,255,0.2); color: white;">
                        <i class="fas fa-filter mr-1"></i> Filtrer Utilisateurs
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-end p-3 shadow"
                        aria-labelledby="filterDropdown" style="min-width: 250px;width: 340px; max-height: 400px; overflow-y: auto;">

                        <div class="mb-2">
                            <input type="text" id="agentSearch" class="form-control form-control-sm"
                                placeholder="Rechercher un utilisateur..." onkeyup="filterList()">
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                            <span class="text-bold small">Utilisateurs</span>
                            <div>
                                <button class="btn btn-xs btn-link p-0 mr-2" onclick="selectAllAgents(true)">Tous</button>
                                <button class="btn btn-xs btn-link p-0 text-danger" onclick="selectAllAgents(false)">Aucun</button>
                            </div>
                        </div>

                        <div id="checkboxList"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body" style="padding-bottom: 0px!important;padding-left: 0px!important;padding-right: 0px!important;">
        <div id="cumulChart" style="width: 100%;"></div>
    </div>
</div>