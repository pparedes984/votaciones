/*
 * File: app/view/pnlCandidato.js
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

Ext.define('voto.view.pnlCandidato', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.pnlcandidato',

    requires: [
        'voto.view.pnlCandidatoViewModel',
        'voto.view.pnlCandidatoViewController',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.grid.Panel',
        'Ext.view.Table',
        'Ext.grid.column.Column',
        'Ext.form.field.ComboBox'
    ],

    controller: 'pnlcandidato',
    viewModel: {
        type: 'pnlcandidato'
    },
    layout: 'fit',
    title: 'Candidatos',

    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'button',
                    id: 'btnNuevoCandidato',
                    iconAlign: 'right',
                    iconCls: 'x-fa fa-plus',
                    text: 'Nuevo',
                    listeners: {
                        click: 'onBtnNuevoCandidatoClick'
                    }
                },
                {
                    xtype: 'button',
                    id: 'btnEliminarCandidato',
                    iconAlign: 'right',
                    iconCls: 'x-fa fa-trash',
                    text: 'Eliminar',
                    listeners: {
                        click: 'onBtnEliminarCandidatoClick'
                    }
                }
            ]
        }
    ],
    items: [
        {
            xtype: 'gridpanel',
            id: 'grdCandidato',
            forceFit: true,
            store: 'Candidato',
            viewConfig: {
                minHeight: 300,
                listeners: {
                    itemdblclick: 'onTableItemDblClick'
                }
            },
            listeners: {
                afterrender: 'onGrdCandidatoAfterRender'
            },
            columns: [
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'nombre',
                    text: 'Nombre',
                    editor: {
                        xtype: 'textfield'
                    }
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'id_tipo_candidato',
                    text: 'Tipo Candidato',
                    editor: {
                        xtype: 'combobox'
                    }
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'id',
                    text: 'id'
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'foto',
                    text: 'foto'
                },
                {
                    xtype: 'gridcolumn',
                    hidden: true,
                    dataIndex: 'id_candidato',
                    text: 'MyColumn11'
                }
            ]
        }
    ]

});