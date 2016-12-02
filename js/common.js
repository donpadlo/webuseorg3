$(function() {     
    $.jgrid.extend({
	setColWidth: function (iCol, newWidth, adjustGridWidth) {
	    return this.each(function () {
		var $self = $(this), grid = this.grid, p = this.p, colName, colModel = p.colModel, i, nCol;
		if (typeof iCol === "string") {
		    colName = iCol;
		    for (i = 0, nCol = colModel.length; i < nCol; i++) {
			if (colModel[i].name === colName) {
			    iCol = i;
			    break;
			}
		    }
		    if (i >= nCol) {
			return; 
		    }
		} else if (typeof iCol !== "number") {
		    return;
		}
		grid.resizing = { idx: iCol };
		grid.headers[iCol].newWidth = newWidth;
		grid.newWidth = p.tblwidth + newWidth - grid.headers[iCol].width;
		grid.dragEnd(); 
		if (adjustGridWidth !== false) {
		    $self.jqGrid("setGridWidth", grid.newWidth, false); 
		}
	    });
	}
    });  

});