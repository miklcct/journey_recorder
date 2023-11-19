/// <reference types="jquery"/>

declare namespace JQueryDeserialize {
    interface DeserializeOptions<TElement> {
        change?(element : Element, value : any) : void;
        complete?($elements : JQuery<TElement>) : void;
        filter? : Parameters<JQuery['filter']>[0];
    }
}

interface JQuery<TElement = HTMLElement> {
    deserialize(
        data : string
        , options? : JQueryDeserialize.DeserializeOptions<TElement> | JQueryDeserialize.DeserializeOptions<TElement>['complete']
    ) : this;
}