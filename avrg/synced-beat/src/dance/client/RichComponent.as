package dance.client {

import com.gskinner.motion.GTweeny;

/** Just a component that adds transitions, managed by DanceView. */
public class RichComponent extends Component
{
    public function transitionIn () :void
    {
    }

    public function transitionOut () :GTweeny
    {
        return null;
    }
}
}
