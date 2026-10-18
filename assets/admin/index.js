// @flow
import {presetWireframeRegistry} from 'sulu-block-suite-bundle';
import {PRELINE_WIREFRAMES} from './wireframes';

Object.keys(PRELINE_WIREFRAMES).forEach((blockType) => {
    if (!presetWireframeRegistry.has(blockType)) {
        presetWireframeRegistry.add(blockType, PRELINE_WIREFRAMES[blockType]);
    }
});

export {PRELINE_WIREFRAMES};
export default PRELINE_WIREFRAMES;
