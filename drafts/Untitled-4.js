 <script>
    // function getSelectedPeriod() {
    //   const val = document.getElementById('periodePicker').value;
    //   const [year, month] = val.split('-');
    //   return {
    //     year: parseInt(year),
    //     month: parseInt(month)
    //   };
    // }
    // const daysLetters = ["S", "D", "L", "M", "M", "J", "V"];
    // const numDays = 30;

    // const headerRow1 = ['', '', {
    //   label: 'Agent',
    //   colspan: 2
    // }, ''];
    // for (let i = 1; i <= numDays; i++) headerRow1.push({
    //   label: String(i),
    //   colspan: 1
    // });
    // const headerRow2 = ['Sté', 'ID', 'Nom', 'Prénom', 'Campagne'];
    // for (let i = 1; i <= numDays; i++) headerRow2.push(daysLetters[(i - 1) % 7]);
    // headerRow2.push('Jours', 'Assiduité', 'Avance', 'Prime', 'CDP', 'Remarque');
    function getDaysInMonth(year, month) {
      return new Date(year, month, 0).getDate();
    }

    function getDayLetter(year, month, day) {
      const date = new Date(year, month - 1, day);
      const days = ["D", "L", "M", "M", "J", "V", "S"];
      return days[date.getDay()];
    }

    function getSelectedPeriod() {
      const val = document.getElementById('periodePicker').value;
      if (!val) {
        const now = new Date();
        return {
          year: now.getFullYear(),
          month: now.getMonth() + 1
        };
      }
      const [year, month] = val.split('-');
      return {
        year: parseInt(year),
        month: parseInt(month)
      };
    }

    function generateHeaders() {
      const {
        year,
        month
      } = getSelectedPeriod();
      const numDays = getDaysInMonth(year, month);

      const headerRow1 = ['', '', {
        label: 'Agent',
        colspan: 2
      }, ''];
      for (let i = 1; i <= numDays; i++) {
        headerRow1.push({
          label: String(i),
          colspan: 1
        });
      }

      const headerRow2 = ['Sté', 'ID', 'Nom', 'Prénom', 'Campagne'];
      for (let i = 1; i <= numDays; i++) {
        headerRow2.push(getDayLetter(year, month, i));
      }
      headerRow2.push('Jours', 'Assiduité', 'Avance', 'Prime', 'CDP', 'Remarque');

      return {
        headerRow1,
        headerRow2,
        numDays
      };
    }
    ///
    function pointageRenderer(instance, td, row, col, prop, value, cellProperties) {
      Handsontable.renderers.TextRenderer.apply(this, arguments);

      const physicalRow = instance.toPhysicalRow(row);
      const rowData = instance.getSourceDataAtRow(physicalRow);

      if (!rowData) return td;

      const ste = rowData[0];
      const idFiscal = rowData[1];
      const nom = rowData[2];
      const prenom = rowData[3];
      const comp = rowData[4];
      const isRowEmpty = (!ste || ste == '') ||
        (!idFiscal || idFiscal == '') ||
        (!nom || nom == '') ||
        (!prenom || prenom == '') ||
        (!comp || comp == '');

      if (col < 5 && !isRowEmpty) {
        cellProperties.readOnly = true;
      } else {
        cellProperties.readOnly = false;
      }
      // const isRowEmpty = !rowData[1];
      // if (col < 5) {
      //   cellProperties.readOnly = !isRowEmpty;
      // }

      let classes = ['htCenter', 'htMiddle'];

      if (value === 'A') {
        classes.push('bg-red');
      } else if (value === 'SB') {
        classes.push('bg-purple');
      } else if (value === 'C') {
        classes.push('bg-light-green');
      } 
      else if (col >= 5 && col < 35) {
        const dayIndex = (col - 5) % 7;
        if (dayIndex === 1) classes.push('sun-cell');
        else if (dayIndex === 0) classes.push('sat-cell');
        else classes.push('white-cell');
      }
      ///
      // else if (col >= 5 && col < (5 + numDays)) {
      //       const day = col - 4;
      //       const dayOfWeek = new Date(year, month - 1, day).getDay(); 
      //       if (dayOfWeek === 0) classes.push('sun-cell'); // Sunday
      //       else if (dayOfWeek === 6) classes.push('sat-cell'); // Saturday
      //       else classes.push('white-cell');
      //   }
      //
      else {
        classes.push('white-cell');
      }

      td.className = classes.join(' ');
      return td;
    }
    let isLoadingComments = false;
    const lastSavedComments = {};
    const sessionV = document.getElementById('sessionV').value;
    const initialHeaders = generateHeaders();
    const hot = new Handsontable(document.getElementById('grid'), {
      id: 'main_pointage_table',
      data: [],
      width: '100%',
      height: sessionV == 'A' ? 400 : 510,
      minSpareRows: 1,
      rowHeaders: true,
      colHeaders: true,
      nestedHeaders: [initialHeaders.headerRow1, initialHeaders.headerRow2],
      fixedColumnsLeft: 5,
      className: 'htCenter htMiddle',
      licenseKey: 'non-commercial-and-evaluation',
      columnSorting: true,
      filters: true,
      comments: true,
      persistentState: true,
      manualColumnResize: true,
      manualRowResize: false,
      renderer: pointageRenderer,
      // colWidths: [40, 50, 70, 70, 90, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 50, 70, 70, 70, 70, 70, 150],
      dropdownMenu: [
        'filter_by_condition',
        'filter_by_value',
        'filter_operators',
        '---------',
        'filter_action_bar',
      ],
      contextMenu: {
        items: {
          "row_above": {
            name: "Insérer ligne au-dessus"
          },
          "row_below": {
            name: "Insérer ligne au-dessous"
          },
          "sp1": "---------",
          "commentsAddEdit": {
            name: "Ajouter une note",
            disabled() {
              const selection = this.getSelectedLast();
              return selection && selection[1] < 5;
            }
          },
          "commentsRemove": {
            name: "Effacer la note",
            disabled() {
              const selection = this.getSelectedLast();
              return selection && selection[1] < 5;
            }
          },
          "sp2": "---------",
          "remove_row": {
            name: "Supprimer la ligne"
          },
        }
      },
      search: true,

      colWidths(index) {

        if (index == 0) {
          return 10;
        }
        if (index == 1) {
          return 30;
        }

        if (index == 4) {
          return 110;
        }

        if (index >= 5 && index < 35) {
          return 35;
        }
        return 100;
      },
      autoColumnSize: true,


      afterColumnSort(currentSortConfig) {
        if (currentSortConfig && currentSortConfig.length > 0) {
          localStorage.setItem('grid_sort_settings', JSON.stringify(currentSortConfig[0]));
        } else {
          localStorage.removeItem('grid_sort_settings');
        }
        this.render();
        console.log('sort')
      },

      afterFilter(conditionsStack) {
        localStorage.setItem('grid_filter_settings', JSON.stringify(conditionsStack));
        this.render();
        console.log('filter')

      },

      // afterGetColHeader(col, TH) {
      //   if (col >= 5 && col < 5 + initialHeaders?.numDays) {
      //     const dayIndex = (col - 5) % 7;
      //     if (dayIndex === 0) TH.classList.add('col-s');
      //     if (dayIndex === 1) TH.classList.add('col-d');
      //   }
      // },
      afterGetColHeader(col, TH) {
            const { year, month } = getSelectedPeriod();
            const numDays = getDaysInMonth(year, month);
            if (col >= 5 && col < 5 + numDays) {
                const day = col - 4;
                const dayOfWeek = new Date(year, month - 1, day).getDay();
                if (dayOfWeek === 6) TH.classList.add('col-s');
                if (dayOfWeek === 0) TH.classList.add('col-d');
            }
        },

      async afterChange(changes, source) {
        if (['loadData', 'updateCalcul', 'populateFromArray'].includes(source) || !changes) return;
        if (source === 'loadData' || source === 'updateCalcul' || !changes) return;

        for (const change of changes) {
          const [row, col, oldVal, newVal] = change;
          if (oldVal === newVal) continue;
          const idFiscal = this.getDataAtCell(row, 1);
          if (!idFiscal) continue;
          if (col >= 5 && col < 35) {
            const total = calculateTotalJours(row);
            this.setDataAtCell(row, 35, total, 'updateCalcul');
            const period = getSelectedPeriod();
            await fetch(`../api/api.php?action=save_pointage&mois=${period.month}&annee=${period.year}`, {
              method: 'POST',
              body: JSON.stringify({
                idFiscal: idFiscal,
                day: col - 4,
                valeur: newVal
              })
            });
          } else {
            await syncAgentRow(row, change);
          }
        }
        loadTimeline()
      },

      afterSetCellMeta: async function(row, col, key, value) {
        if (isLoadingComments) return;
        if (key === 'comment') {
          const idFiscal = this.getDataAtCell(row, 1);
          if (!idFiscal) return;

          const period = getSelectedPeriod();
          const commentText = (value && value.value) ? value.value : null;
          await fetch(`../api/api.php?action=save_comment&mois=${period.month}&annee=${period.year}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              idFiscal: idFiscal,
              day: col - 4,
              comment: commentText
            })
          });

          loadTimeline();
        }
      },

      beforeRemoveRow: async function(index, amount) {
        const idsToDelete = [];
        for (let i = 0; i < amount; i++) {
          const id = this.getDataAtCell(index + i, 1);
          if (id) idsToDelete.push(id);
        }

        if (idsToDelete.length === 0) return true;

        const msg = idsToDelete.length === 1 ?
          `Voulez-vous vraiment supprimer l'agent ${idsToDelete[0]} ?` :
          `Voulez-vous vraiment supprimer ces ${idsToDelete.length} agents ?`;

        if (confirm(msg)) {
          try {
            const requests = idsToDelete.map(id =>
              fetch(`../api/api.php?action=delete_agent&idFiscal=${id}`)
            );
            await Promise.all(requests);
            return true;
          } catch (err) {
            alert("Erreur lors de la suppression groupée.");
            console.error(err);
            return false;
          }
        }
        return false;
      }

    });


    async function syncAgentRow(row, changes) {
      const columnNames = {
        0: 'STE',
        1: 'ID Fiscal',
        2: 'Nom',
        3: 'Prénom',
        4: 'Campagne',
        36: 'Assiduité',
        37: 'Avance',
        38: 'Prime',
        39: 'CDP',
        40: 'Remarque'
      };

      const [row_, col, oldVal, newVal] = changes;
      const columnName = columnNames[col] || `Colonne ${col}`;
      const period = getSelectedPeriod();

      const data = hot.getDataAtRow(row);
      const agent = {
        ste: data[0] || '',
        idFiscal: data[1] || '',
        nom: data[2] ? data[2].toUpperCase() : '',
        prenom: data[3] || '',
        campagne: data[4] || '',
        totalJours: data[35] || 0,
        assiduite: data[36] || '',
        avance: data[37] || '',
        prime: data[38] || '',
        cdp: data[39] || '',
        remarque: data[40] || '',
        mois: period.month,
        annee: period.year,
        updatedRow: columnName
      };
      if (agent.idFiscal) {
        await fetch(`../api/api.php?action=add_agent&mois=${period.month}&annee=${period.year}`, {
          method: 'POST',
          body: JSON.stringify(agent)
        });
      }
    }

    function calculateTotalJours(row) {
      const data = hot.getSourceDataAtRow(row);
      let total = 0;
      for (let i = 0; i < numDays; i++) {
        const valRaw = hot.getDataAtCell(row, 5 + i);
        const val = parseFloat(String(valRaw).replace(',', '.')) || 0;
        const divisor = ((i % 7) === 0) ? 4 : 8;
        total += (val / divisor);
      }

      const period = getSelectedPeriod();
      const agent = {
        ste: data[0],
        idFiscal: data[1],
        nom: data[2],
        prenom: data[3],
        campagne: data[4],
        totalJours: total,
        assiduite: data[36],
        avance: data[37],
        prime: data[38],
        cdp: data[39],
        remarque: data[40],
        mois: period.month,
        annee: period.year,
        updatedRow: 'totalJours'
      };

      fetch(`../api/api.php?action=add_agent&mois=${period.month}&annee=${period.year}`, {
          method: 'POST',
          body: JSON.stringify(agent),
          headers: {
            'Content-Type': 'application/json'
          }
        })
        .then(res => res.json())
      return total.toFixed(2);
    }

    async function loadData() {
      isLoadingComments = true;
      const picker = document.getElementById('periodePicker');
      let periodValue = picker.value;
      if (!periodValue) {
        const now = new Date();
        const currentYear = now.getFullYear();
        const currentMonth = String(now.getMonth() + 1).padStart(2, '0');
        periodValue = `${currentYear}-${currentMonth}`;
        picker.value = periodValue;
      }
      const [year, month] = periodValue.split('-');
      const newHeaders = generateHeaders();
      hot.updateSettings({
        nestedHeaders: [newHeaders.headerRow1, newHeaders.headerRow2]
      });
      const res = await fetch(`../api/api.php?action=fetch&annee=${year}&mois=${parseInt(month)}`);
      let data = await res.json();

      // if (!data || data.length === 0) {
      //   data = [new Array(headerRow2.length).fill('')];
      // }

      const tableData = data.map(item => item.data);
      hot.updateSettings({
        cell: []
      });
      hot.loadData(tableData);

      const commentsPlugin = hot.getPlugin('comments');
      data.forEach((item, rowIndex) => {
        item.comments.forEach((commentText, dayIdx) => {
          if (commentText && commentText.trim() !== "") {
            commentsPlugin.setCommentAtCell(rowIndex, dayIdx + 5, commentText);
          }
        });
      });

      hot.render();
      setTimeout(() => {
        isLoadingComments = false;
      }, 100);

      const savedFilters = localStorage.getItem('grid_filter_settings');
      if (savedFilters) {
        const filtersPlugin = hot.getPlugin('filters');
        const stack = JSON.parse(savedFilters);
        filtersPlugin.removeConditions();
        stack.forEach(item => {
          item.conditions.forEach(cond => {
            filtersPlugin.addCondition(item.column, cond.name, cond.args);
          });
        });
        filtersPlugin.filter();
      }

      const savedSort = localStorage.getItem('grid_sort_settings');
      if (savedSort) {
        const sortConfig = JSON.parse(savedSort);
        hot.getPlugin('columnSorting').sort(sortConfig);
      }
    }

    async function loadTimeline(idFiscal = null) {
      const container = document.querySelector('.timeline');
      if (!container) return;

      let url = '../api/api.php?action=fetch_history';
      if (idFiscal) url += `&idFiscal=${encodeURIComponent(idFiscal)}`;

      try {
        const response = await fetch(url);
        if (!response.ok) throw new Error('Network response was not ok');

        const logs = await response.json();

        let html = '';
        let lastDate = '';


        // let unwantedL = logs.filter(log =>
        //   log.action === "update_agent_Prénom" ||
        //   log.action === "update_agent_Nom" ||
        //   log.action === "update_agent_ID Fiscal" ||
        //   log.action === "update_agent_Campagne" ||
        //   log.action === "update_agent_STE"
        // ).length

        // console.log(logs.length/*, unwantedL*/)
        if (logs.length === 0 /*|| unwantedL == logs.length*/ ) {
          container.innerHTML = '<div class="text-center p-3">Aucun historique trouvé.</div>';
          return;
        }

        logs.forEach(log => {
          const authorName = `${log.author_nom || ''} ${log.author_prenom || ''}`.trim() || log.updated_by;
          const targetName = `${log.target_nom || ''} ${log.target_prenom || ''}`.trim() || log.idFiscal;

          const dateOptions = {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
          };
          const currentDate = new Date(log.created_at).toLocaleDateString('fr-FR', dateOptions);

          if (currentDate !== lastDate) {
            html += `
      <div class="time-label">
        <span class="text-bg-danger">${currentDate}</span>
      </div>`;
            lastDate = currentDate;
          }

          const periode = `${String(log.mois).padStart(2, '0')}/${log.annee}`;

          let detailsObj = {};
          try {
            detailsObj = JSON.parse(log.details);
          } catch (e) {
            detailsObj = {};
          }

          let config = {};

          if (log.action === 'save_pointage') {
            const day = detailsObj.day || '?';
            const val = detailsObj.valeur || '';

            config = {
              icon: 'bi-calendar-check',
              color: 'text-bg-warning',
              message: `<a href="#"><strong>${authorName}</strong></a> a mis à jour le pointage de <strong>${targetName}</strong> pour la periode du
                        <small class="badge text-bg-secondary">${periode}</small>
                        <span class="text-muted">Jour ${day} pointé à : </span>
                        <small class="badge text-bg-secondary ms-1">${val}</small>`
            };
          }
          // else if (log.action == "update_agent_Prénom" || log.action == "update_agent_Nom" || log.action == "update_agent_Campagne" || log.action == "update_agent_ID Fiscal" || log.action == "update_agent_STE") {
          //   // console.log(log.action,'-----')
          //   return
          // }
          // else if (log.action == "update_agent_totalJours") {
          //   // console.log(log.action,'-----')
          //   return
          // }
          else {
            const actionText = log.action === 'add_agent' ? 'créé' : 'mis à jour';
            const color = log.action === 'add_agent' ? 'text-bg-success' : 'text-bg-info';
            const icon = log.action === 'add_agent' ? 'bi-person-plus' : 'bi-person-gear';
            let last = log?.action?.split('_').pop().toLowerCase() ?? '_invalide';
            let val;
            //= last == "assiduité" ? detailsObj['assiduite'] : "commentaire" ? detailsObj['comment'] : detailsObj[last];
            if (last == "assiduité") {
              val = detailsObj['assiduite']
            } else if (last == "commentaire") {
              val = detailsObj['comment']
            } else {
              val = detailsObj[last]
            }
            const capitalized = last.charAt(0).toUpperCase() + last.slice(1);
            let _text = '';
            if (last == 'assiduité' || last == 'avance') {
              _text = 'l\'' + last
            } else if (last == 'prime' || last == 'remarque') {
              _text = 'la ' + last
            } else {
              _text = 'le ' + last
            }
            config = {
              icon: icon,
              color: color,
              message: `<a href="#"><strong>${authorName}</strong></a> a ${actionText + ' ' + _text} de l'agent <strong>${targetName}</strong> pour la periode du
                      <small class="badge text-bg-secondary ms-1">${periode}</small>
                      <span class="text-muted">${capitalized} : </span>
                      <small class="badge text-bg-secondary ms-1">${val}</small>`
            };
          }

          html += `
        <div>
          <i class="timeline-icon bi ${config.icon} ${config.color} mt-1"></i>
          <div class="timeline-item shadow-sm">
            <span class="time"><i class="bi bi-clock-fill"></i> ${log.log_time.substring(0, 5)}</span>
            <div class="timeline-header no-border">
              ${config.message}
            </div>
          </div>
        </div>`;
        });

        html += `
      <div>
        <i class="timeline-icon bi bi-clock-fill text-bg-secondary"></i>
      </div>`;

        container.innerHTML = html;

      } catch (error) {
        console.error("Erreur lors du chargement de la timeline:", error);
        container.innerHTML = '<div class="text-danger p-3">Erreur de chargement de l\'historique.</div>';
      }
    }

    async function saveAgent() {
      const period = getSelectedPeriod();
      const agent = {
        ste: document.getElementById('m_ste').value,
        idFiscal: document.getElementById('m_id').value,
        nom: document.getElementById('m_nom').value.toUpperCase(),
        prenom: document.getElementById('m_prenom').value,
        campagne: document.getElementById('m_camp').value
      };
      if (!agent.idFiscal) return alert("ID requis");

      await fetch(`../api/api.php?action=add_agent&mois=${period.month}&annee=${period.year}`, {
        method: 'POST',
        body: JSON.stringify(agent)
      });
      closeModal();
      loadData();
    }

    function openModal() {
      document.getElementById('modal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('modal').style.display = 'none';
      ['m_ste', 'm_id', 'm_nom', 'm_prenom', 'm_camp'].forEach(id => document.getElementById(id).value = '');
    }

    function toggleMenu() {
      const menu = document.getElementById('dropdown');
      menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }
    ///
    // const exportBtn = document.getElementById('export-file');

    // exportBtn.addEventListener('click', async () => {
    //   console.log('ttttt')
    // });
    ///

    function clearAllSettings() {
      localStorage.removeItem('grid_filter_settings');
      localStorage.removeItem('grid_sort_settings');
      localStorage.clear();
      location.reload();
    }

    loadData();
    loadTimeline();
  </script>