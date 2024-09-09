{{R3M}}
{{$register = Package.Raxon.Event:Init:register()}}
{{if(!is.empty($register))}}
{{Package.Raxon.Event:Import:role.system()}}
{{Package.Raxon.Event:Import:event.action()}}
{{Package.Raxon.Event:Import:event()}}
Import System.Event
{{/if}}