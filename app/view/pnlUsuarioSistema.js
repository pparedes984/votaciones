/*
 * File: app/view/pnlUsuarioSistema.js
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

Ext.define('voto.view.pnlUsuarioSistema', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.pnlusuariosistema',

    requires: [
        'voto.view.pnlUsuarioSistemaViewModel',
        'voto.view.pnlUsuarioSistemaViewController',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.grid.Panel',
        'Ext.view.Table',
        'Ext.grid.column.Column'
    ],

    controller: 'pnlusuariosistema',
    viewModel: {
        type: 'pnlusuariosistema'
    },
    layout: 'fit',
    title: 'Usuario Sistema',

    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'button',
                    id: 'btnNuevoUs',
                    iconAlign: 'right',
                    iconCls: 'x-fa fa-plus',
                    text: 'Nuevo',
                    listeners: {
                        click: 'onBtnNuevoUsClick'
                    }
                },
                {
                    xtype: 'button',
                    id: 'btnEliminarUs',
                    iconAlign: 'right',
                    iconCls: 'x-fa fa-trash',
                    text: 'Eliminar',
                    listeners: {
                        click: 'onBtnEliminarUsClick'
                    }
                }
            ]
        }
    ],
    items: [
        {
            xtype: 'gridpanel',
            id: 'grdUsuarioSistema',
            forceFit: true,
            store: 'UsuarioSistema',
            viewConfig: {
                minHeight: 300
            },
            columns: [
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'usuario',
                    text: 'Usuario'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'nombre',
                    text: 'Nombre'
                },
                {
                    xtype: 'gridcolumn',
                    dataIndex: 'rol_descripcion',
                    text: 'Rol'
                }
            ],
            listeners: {
                afterrender: 'onGrdUsuarioSistemaAfterRender'
            }
        }
    ]

});