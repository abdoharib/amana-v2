export default [
  {
    title: 'الرئيسية',
    to: { name: 'root' },
    icon: { icon: 'tabler-smart-home' },
  },
  {
    title: 'المراحل التعليمية',
    to: { name: 'education-stages' },
    icon: { icon: 'tabler-file' },

  },
  {
    title: 'محتوى المراحل',

    icon: { icon: 'tabler-file' },
    children: [
      {
        title: 'المحتوى',
        to: { name: 'educational-content' },
      },
      {
        title: 'العلم والاكتشاف',
        to: { name: 'items' },
      },
      {
        title: 'الاذكار والادعية',
        to: { name: 'prayers' },
      },
      {
        title: 'قصص الانبياء',
        to: { name: 'prophet-stories' },
      },
      {
        title: 'القصص',
        to: { name: 'stories' },
      },
      {
        title: 'اعمال الصدقة',
        to: { name: 'charity-work' },
      },
      {
        title: 'القيم الاخلاقية',
        to: { name: 'moral-values' },
      },
      {
        title: 'الارقام',
        to: { name: 'numbers' },
      },
      {
        title: 'الحروف',
        to: { name: 'letters' },
      },
    ],
  },
  {
    title: 'النصائح الأبوية',
    to: { name: 'advices' },
    icon: { icon: 'tabler-file' },
  },
  {
    title: 'مجتمع الاباء',
    to: { name: 'posts' },
    icon: { icon: 'tabler-file' },
  },


  {
    title: 'المستخدمين',
    icon: { icon: 'tabler-user' },
    children: [
      {
        title: 'المشرفين',
        to: { name: 'users-type', params: { type: 'admins' } }
      },
      {
        title: 'الأباء',
        to: { name: 'users-type', params: { type: 'guardians' } }
      },
      {
        title: 'الابناء',
        to: 'kids',
      },

    ],
  },
  {
    title: 'الاعدادات',
    icon: { icon: 'tabler-settings' },
    children: [
      {
        title: 'إرسال إشعار',
        to: { name: 'notifications' },
      },
    ],
  }
]
