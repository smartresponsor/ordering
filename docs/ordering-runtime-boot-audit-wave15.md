# Ordering runtime boot audit - wave 15

Wave 15 focused on configuration/container cleanup rather than structural deletion.

Highlights:
- retargeted route and API Platform controller references to existing `App\Controller\Order\...` classes
- added minimal local DTO/provider/processor/handler/projector classes required by auto-loaded config files
- reduced config-level missing `App\...` references from 100 to 67

This wave improves boot coherence, but does not yet make the application fully runtime-ready. The remaining config debt sits in larger missing subsystems and should be handled as targeted restoration waves.
