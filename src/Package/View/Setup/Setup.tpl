{{R3M}}
{{$register = Package.Raxon.Org.Event:Init:register()}}
{{if(!is.empty($register))}}
{{Package.Raxon.Org.Event:Import:role.system()}}
{{Package.Raxon.Org.Event:Import:event.action()}}
{{Package.Raxon.Org.Event:Import:event()}}
Import System.Event
{{/if}}