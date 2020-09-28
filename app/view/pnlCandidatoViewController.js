/*
 * File: app/view/pnlCandidatoViewController.js
 *
 * This file was generated by Sencha Architect version 4.2.4.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 6.6.x Classic library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 6.6.x Classic. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('voto.view.pnlCandidatoViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.pnlcandidato',

    onBtnNuevoCandidatoClick: function(button, e, eOpts) {
        var win = Ext.create('voto.view.wndCandidato');
        win.show();
    },

    onBtnEliminarCandidatoClick: function(button, e, eOpts) {
        //Selecciona un registro y verifica que no tenga votos para eliminarlo
        var g = Ext.getCmp('grdCandidato');
        var arrayKeys = g.getSelectionModel().selected.items;
        var indice = g.getSelectionModel().selectionStartIdx;
        if(arrayKeys.length === 0)
        Ext.Msg.alert('Error', 'Debe escoger un candidato');
        else
        {
            var store = g.getStore();
            var rec = g.selection;
            Ext.Ajax.request({
                url: '../servidor/voto/candidato/verificavoto',
                method:"post",
                params: {
                    id: rec.data.id
                },
                success: function( result, request ) {
                    var responseData = Ext.JSON.decode(result.responseText);
                    if(responseData.success)
                    {

                        store.remove(rec);
                        store.sync(
                        {
                            params:{
                                id: rec.data.id
                            },
                            success: function(batch, success)
                            {
                                store.commitChanges();
                                store.load();
                                Ext.Msg.alert('Alerta', 'Se ha eliminado el registro exitosamente');
                            },

                            failure: function(batch, success)
                            {
                                Ext.Msg.alert('Error', 'Hubor un error');
                            }
                        });
                    }
                    else
                    {
                        Ext.Msg.alert('Alerta','Este registro tiene votos asociados.');
                    }
                },

                failure: function(response, opts) {
                    console.log('server-side failure with status code ' + response.status);
                }
            });

        }
    },

    onTableItemDblClick: function(dataview, record, item, index, e, eOpts) {
        //Carga la infromación del candidato en la ventana de modificación y obtiene el
        //id del candidato seleccionado para modificarlo
        var grid = Ext.getCmp('grdCandidato');
        var arrayKeys = grid.getSelectionModel().selected.items;
        var indice = grid.getSelectionModel().selectionStartIdx;
        ID = arrayKeys[0].data.id;
        var win = Ext.create('voto.view.wndCandidatoM');
        win.show();
        Ext.getCmp('txtNombreCM').setValue(arrayKeys[0].data.nombre);
        console.log(arrayKeys[0].data.id_tipo_candidato);
        Ext.getCmp('cbTipoCandidatoM').setValue(arrayKeys[0].data.id_candidato);
        URL = '<div style="display: flex; justify-content: center;"><img src="';
        URL = URL.concat(arrayKeys[0].data.foto);
        arr =arrayKeys[0].data.foto.split('/');
        x = arr[3];
        URL = URL.concat('" height = "124"></div>');
        Ext.getCmp('id_panel_foto1').setHtml(URL);

    },

    onGrdCandidatoAfterRender: function(component, eOpts) {
        //Carga la grid de candidatos luego de guardar o actualizar un registro
        var store = Ext.getCmp('grdCandidato').getStore();
        store.proxy.extraParams = {
            busqueda: 1
        };
        store.removeAll();
        store.load();
    }

});
